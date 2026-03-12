<?php
require_once ROOT . "/app/models/user/userModel.php";
require_once ROOT . '/app/core/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $pdo = Database::getPDO();

    $userModel = new UserModel($pdo);
	$userModel->logout();
    redirect('home');
} else {
	redirect('home');
}