<?php

class PostService {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

	public function registerPost($imageDatas)
	{
		// 1. Décoder l'image (Base64 -> binaire)
		$img = str_replace('data:image/png;base64,', '', $imageDatas['image']);
		$img = str_replace(' ', '+', $img);
		$background = imagecreatefromstring(base64_decode($img));

		// 2. Charger le sticker
		$stickerPath = ROOT . '/public/assets/stickers/' . basename($imageDatas['sticker']);
		$sticker = imagecreatefrompng($stickerPath);

		// 3. Montage avec GD (Resampled pour la qualité)
		list($origW, $origH) = getimagesize($stickerPath);
		$positionX = (int) round((float) $imageDatas['x']);
		$positionY = (int) round((float) $imageDatas['y']);
		$width = (int) round((float) $imageDatas['w']);
		$height = (int) round((float) $imageDatas['h']);
		imagecopyresampled(
		    $background, $sticker,
		    $positionX, $positionY, 0, 0,
		    $width, $height, $origW, $origH
		);

		// 4. Sauvegarder le fichier
		$filename = uniqid('camagru_') . '.png';
		$savePath = ROOT . '/public/uploads/' . $filename;

		if (imagepng($background, $savePath)) {
		    // 5. Enregistrer en Base de données
		    $insertRequest = $this->db->prepare("INSERT INTO posts (user_id, filename) VALUES (?, ?)");
		    $insertRequest->execute([$_SESSION['user_id'], $filename]);

		    echo json_encode(['success' => true]);
		} else {
		    echo json_encode(['success' => false, 'message' => "Impossible d'écrire le fichier"]);
		}

		imagedestroy($background);
		imagedestroy($sticker);
	}

	public function getPaginatedPosts($limit, $offset, $currentUserId) {
		$sql = "SELECT 
                p.*,
                (SELECT COUNT(*) FROM likes WHERE post_id = p.id AND user_id = :uid) AS user_has_liked
            FROM posts AS p
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset";

	    $dbRequest = $this->db->prepare($sql); 
	    $dbRequest->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
	    $dbRequest->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
		$dbRequest->bindValue(':uid', (int) $currentUserId, PDO::PARAM_INT);
	    $dbRequest->execute();
	    
	    return $dbRequest->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getTotalPostsCount() {
	    return $this->db->query("SELECT COUNT(*) FROM posts")->fetchColumn();
	}

	public function toggleLike($userId, $postId) {
	    $selectRequest = $this->db->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ?");
	    $selectRequest->execute([$userId, $postId]);
	    
	    if ($selectRequest->fetch()) {
	        $deleteRequest = $this->db->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
			$deleteRequest->execute([$userId, $postId]);
			$decreaseRequest = $this->db->prepare("UPDATE posts SET likes_count = likes_count - 1 WHERE id = ? AND likes_count > 0");
			$decreaseRequest->execute([$postId]);
	        return 'unliked';
	    } else {
	        $insertRequest = $this->db->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
			$insertRequest->execute([$userId, $postId]);
			$increaseRequest = $this->db->prepare("UPDATE posts SET likes_count = likes_count + 1 WHERE id = ?");
			$increaseRequest->execute([$postId]);
	        return 'liked';
	    }
	}

	public function addComment($userId, $postId, $content) {
	    $stmt = $this->db->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)");
	    return $stmt->execute([$userId, $postId, htmlspecialchars($content)]);
	}
}