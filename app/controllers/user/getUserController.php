<?php
require_once ROOT . "/app/services/user/userService.php";
require_once ROOT . '/app/core/database.php';
    
$pdo = Database::getPDO();

$userService = new UserService($pdo);

$user = $userService->getUserById($_SESSION['user_id']);

if (!$user)
	redirect('logout');

require_once ROOT . '/includes/header.php';
require_once ROOT . '/app/views/user/profileView.php';
require_once ROOT . '/includes/footer.php';