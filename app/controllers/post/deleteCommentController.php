<?php
require_once ROOT . '/app/core/database.php';
require_once ROOT . '/app/services/post/postService.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
	http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non connecté']);
    exit;
}

$headers = getallheaders();
$isAjax = isset($headers['X-CSRF-TOKEN']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
if (!isset($headers['X-CSRF-TOKEN']) || !hash_equals($_SESSION['csrf_token'], $headers['X-CSRF-TOKEN'])) {
    if ($isAjax) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Non autorisée']);
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Non autorisé.'];
        header('Location: index.php?action=studio');
    }
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$commentId = $data['comment_id'] ?? null;

if ($commentId) {
    $postService = new PostService(Database::getPDO());
    $comment = $postService->getCommentById($commentId);

    if ($comment && $comment['user_id'] == $_SESSION['user_id']) {
        
        $postService->deleteComment($commentId);

        echo json_encode(['success' => true]);
    } else {
		http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Action non autorisée ou post inexistant']);
    }
}