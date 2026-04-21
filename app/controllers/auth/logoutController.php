<?php
require_once ROOT . "/app/services/auth/authService.php";
require_once ROOT . '/app/core/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Non autorisé'];
		exit;
    }
    
    $pdo = Database::getPDO();

    $authService = new AuthService($pdo);
	$authService->logout();
    redirect('home');
} else {
	redirect('home');
}