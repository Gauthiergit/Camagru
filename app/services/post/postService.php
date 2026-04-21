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

			$getRequest = $this->db->prepare("SELECT id FROM posts WHERE filename = ?");
			$getRequest->execute([$filename]);
			$post = $getRequest->fetch(PDO::FETCH_ASSOC);

		    echo json_encode(['success' => true, 'filename' => $filename, 'post_id' => $post['id']]);
		} else {
		    echo json_encode(['success' => false, 'message' => "Impossible d'écrire le fichier"]);
		}

		imagedestroy($background);
		imagedestroy($sticker);
	}

	public function getPaginatedPosts($limit, $offset, $currentUserId) {
		$sql = "SELECT 
                p.*,
                (SELECT COUNT(*) FROM likes WHERE post_id = p.id AND user_id = :uid) AS user_has_liked,
				(SELECT json_agg(
		            json_build_object(
		                'username', u.username,
		                'content', c.content,
		                'created_at', c.created_at
            		))
			        FROM comments c
			        JOIN users u ON c.user_id = u.id
			        WHERE c.post_id = p.id
				) AS comments_list
            FROM posts AS p
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset";

	    $dbRequest = $this->db->prepare($sql); 
	    $dbRequest->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
	    $dbRequest->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
		$dbRequest->bindValue(':uid', (int) $currentUserId, PDO::PARAM_INT);
	    $dbRequest->execute();
	    
		$posts = $dbRequest->fetchAll(PDO::FETCH_ASSOC);
		foreach ($posts as &$post) {
		    $post['comments_list'] = $post['comments_list'] ? json_decode($post['comments_list'], true) : [];
		}

	    return $posts;
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
	    $insertRequest = $this->db->prepare(
	        "INSERT INTO comments (user_id, post_id, content)
	         VALUES (:user_id, :post_id, :content)
	         RETURNING id, user_id, post_id, content, created_at"
	    );
	    $insertRequest->execute([
	        'user_id' => $userId,
	        'post_id' => $postId,
	        'content' => $content
	    ]);

	    return $insertRequest->fetch(PDO::FETCH_ASSOC);
	}

	public function getPostOwnerId($postId)
	{
		$dbRequest = $this->db->prepare("SELECT user_id FROM posts WHERE id = ?");
		$dbRequest->execute([$postId]);
		$result = $dbRequest->fetch(PDO::FETCH_ASSOC);
		return $result['user_id'] ?? null;
	}

	public function getUserPosts($userId) {
	    $dbRequest = $this->db->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
	    $dbRequest->execute([$userId]);
	    return $dbRequest->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getPostById($postId) {
	    $dbRequest = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
	    $dbRequest->execute([$postId]);
	    return $dbRequest->fetch(PDO::FETCH_ASSOC);
	}

	public function deletePost($postId) {
	    $dbRequest = $this->db->prepare("DELETE FROM posts WHERE id = ?");
	    return $dbRequest->execute([$postId]);
	}

	public function getPostDetails($postId, $currentUserId) {
	    $sql = "SELECT 
	                p.*, 
	                u.username AS author,
	                (SELECT COUNT(*) FROM likes WHERE post_id = p.id AND user_id = :uid) AS user_has_liked,
	                (
	                    SELECT json_agg(
	                        json_build_object(
								'comment_id', c.id,
	                            'username', cu.username,
								'user_id', c.user_id,
	                            'content', c.content,
	                            'created_at', c.created_at
	                        ) ORDER BY c.created_at ASC
	                    )
	                    FROM comments c
	                    JOIN users cu ON c.user_id = cu.id
	                    WHERE c.post_id = p.id
	                ) AS comments_list
	            FROM posts p
	            JOIN users u ON p.user_id = u.id
	            WHERE p.id = :pid";

	    $dbRequest = $this->db->prepare($sql);
	    $dbRequest->execute(['pid' => $postId, 'uid' => $currentUserId]);
	    $post = $dbRequest->fetch(PDO::FETCH_ASSOC);

	    if ($post) {
	        $post['comments_list'] = $post['comments_list'] ? json_decode($post['comments_list'], true) : [];
	    }
	    return $post;
	}

	public function getCommentById($commentId) {
	    $dbRequest = $this->db->prepare("SELECT * FROM comments WHERE id = ?");
	    $dbRequest->execute([$commentId]);
	    return $dbRequest->fetch(PDO::FETCH_ASSOC);
	}

	public function deleteComment($commentId) {
	    $dbRequest = $this->db->prepare("DELETE FROM comments WHERE id = ?");
	    return $dbRequest->execute([$commentId]);
	}
}