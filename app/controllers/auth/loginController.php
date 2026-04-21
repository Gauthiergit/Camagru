<?php
require_once ROOT . "/app/services/auth/authService.php";
require_once ROOT . '/app/core/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	$_SESSION['flash'] = ['type' => 'danger', 'message' => 'Méthode non autorisée'];
	redirect('home');
}
    
$pdo = Database::getPDO();

$authService = new AuthService($pdo);

$result = $authService->login(
    $_POST['login'],
    $_POST['password'],
);

if (is_array($result)) {
    $_SESSION['user_id'] = $result['id'];
    $_SESSION['username'] = $result['username'];

	if (empty($_SESSION['csrf_token'])) {
	    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}
    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Ravi de vous revoir, ' . $result['username'] . ' !'];
    redirect('studio');
} else {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => $result];
    redirect('login-form');
}