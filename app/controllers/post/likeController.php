<?php
header('Content-Type: application/json; charset=utf-8');

require_once ROOT . '/app/core/database.php';
require_once ROOT . '/app/services/post/postService.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$postId = $data['post_id'] ?? null;

if (isset($_SESSION['user_id'])) {
	try {
		if ($postId) {
		    $postService = new PostService(Database::getPDO());
		    $result = $postService->toggleLike($_SESSION['user_id'], $postId);
		    
		    echo json_encode([
		        'success' => true, 
		        'status' => $result
		    ]);
		}
	} catch (Throwable $th) {
		http_response_code(500);
		echo json_encode([
			'success' => false,
			'message' => 'Erreur pendant changement du like'
		]);
	}
} else {
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
}