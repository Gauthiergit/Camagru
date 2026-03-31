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
		imagecopyresampled(
		    $background, $sticker,
		    $imageDatas['x'], $imageDatas['y'], 0, 0,
		    $imageDatas['w'], $imageDatas['h'], $origW, $origH
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
}