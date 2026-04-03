<?php
require_once ROOT . '/app/core/database.php';
require_once ROOT . '/app/services/post/postService.php';

$postId = $_GET['id'] ?? null;
if (!$postId) { redirect('gallery'); exit; }

$postService = new PostService(Database::getPDO());
$currentUserId = $_SESSION['user_id'] ?? 0;
$post = $postService->getPostDetails($postId, $currentUserId);

if (!$post) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Post introuvable.'];
    redirect('gallery');
    exit;
}

require_once ROOT . '/includes/header.php';
require_once ROOT . '/app/views/postDetailView.php';
require_once ROOT . '/includes/footer.php';