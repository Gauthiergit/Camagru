<?php
require_once ROOT . '/app/core/database.php';
require_once ROOT . '/app/services/post/postService.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
	http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$postId = $data['post_id'] ?? null;

if ($postId) {
    $postService = new PostService(Database::getPDO());
    $post = $postService->getPostById($postId);

    // Vérification de sécurité : le post existe et appartient à l'user
    if ($post && $post['user_id'] == $_SESSION['user_id']) {
        
        // 1. Supprimer le fichier physique
        $filePath = ROOT . '/public/uploads/' . $post['filename'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // 2. Supprimer l'entrée en base de données
        // (Les likes et commentaires seront supprimés via ON DELETE CASCADE si configuré)
        $postService->deletePost($postId);

        echo json_encode(['success' => true]);
    } else {
		http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Action non autorisée ou post inexistant']);
    }
}