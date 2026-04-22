<?php
require_once ROOT . "/app/services/user/userService.php";
require_once ROOT . '/app/core/database.php';

if (!isset($_SESSION['user_id'])) {
   $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Non connecté'];
    redirect('login-form');
}
    
$pdo = Database::getPDO();

$userService = new UserService($pdo);

$user = $userService->getUserById($_SESSION['user_id']);

if (!$user)
	redirect('logout');

require_once ROOT . '/includes/header.php';
require_once ROOT . '/app/views/user/profileView.php';
require_once ROOT . '/includes/footer.php';