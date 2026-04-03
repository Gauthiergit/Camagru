<?php
require_once ROOT . '/app/core/database.php';
require_once ROOT . '/app/services/post/postService.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Veuillez vous connecter.'];
    redirect('login-form');
    exit;
}

$postService = new PostService(Database::getPDO());
$userPosts = $postService->getUserPosts($_SESSION['user_id']);

$stickers = ['chapeau.png', 'lunettes.png', 'barbe_hippie.png', 'chat_effraye.png', 'chat_volant.png'];

require_once ROOT . '/includes/header.php';
require_once ROOT . '/app/views/user/userStudioView.php';
require_once ROOT . '/includes/footer.php';