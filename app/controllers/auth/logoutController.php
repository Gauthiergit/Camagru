<?php
require_once ROOT . "/app/services/auth/authService.php";
require_once ROOT . '/app/core/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $pdo = Database::getPDO();

    $authService = new AuthService($pdo);
	$authService->logout();
    redirect('home');
} else {
	redirect('home');
}