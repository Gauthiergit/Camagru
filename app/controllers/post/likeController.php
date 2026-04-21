<?php
header('Content-Type: application/json; charset=utf-8');

require_once ROOT . '/app/core/database.php';
require_once ROOT . '/app/services/post/postService.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$postId = $data['post_id'] ?? null;

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non connecté']);
    exit;
}

$headers = getallheaders();
$receivedToken = $headers['X-CSRF-TOKEN'] ?? '';
if (!hash_equals($_SESSION['csrf_token'], $receivedToken)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

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