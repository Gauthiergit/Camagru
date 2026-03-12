<?php
require_once ROOT . "/app/models/user/userModel.php";
require_once ROOT . '/app/core/database.php';
    
$pdo = Database::getPDO();

$userModel = new UserModel($pdo);

$user = $userModel->getUserById($_SESSION['user_id']);

if (!$user)
	redirect('logout');

require_once ROOT . '/includes/header.php';
require_once ROOT . '/app/views/user/profileView.php';
require_once ROOT . '/includes/footer.php';