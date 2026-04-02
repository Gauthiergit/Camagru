<?php

require_once ROOT . "/app/services/post/postService.php";
require_once ROOT . "/app/services/user/userService.php";
require_once ROOT . "/app/services/mail/mailService.php";
require_once ROOT . '/app/core/database.php';

if (!isset($_SESSION['user_id'])) {
	$_SESSION['flash'] = ['type' => 'danger', 'message' => 'Connectez-vous pour pouvoir commenter un post'];
    redirect("login-form");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $pdo = Database::getPDO();

    $postService = new PostService($pdo);
	$userSevice = new UserService($pdo);
	$mailService = new MailService();

    $postService->addComment($_SESSION['user_id'], $_POST['post_id'], $_POST['content']);
	$ownerId = $postService->getPostOwnerId($_POST['post_id']);
	$owner = $userSevice->getUserById($ownerId);
	$mailService->sendNotification($owner['email'], $owner['wants_notifs']);
	
	redirect('gallery');
}