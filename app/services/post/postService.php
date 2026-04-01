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

	public function getPaginatedPosts($limit, $offset) {
	    $dbRequest = $this->db->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
	    
	    $dbRequest->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
	    $dbRequest->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
	    $dbRequest->execute();
	    
	    return $dbRequest->fetchAll();
	}

	public function getTotalPostsCount() {
	    return $this->db->query("SELECT COUNT(*) FROM posts")->fetchColumn();
	}
}