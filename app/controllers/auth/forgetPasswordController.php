<?php
require_once ROOT . "/app/services/auth/authService.php";
require_once ROOT . "/app/services/mail/mailService.php";
require_once ROOT . '/app/core/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	$_SESSION['flash'] = ['type' => 'danger', 'message' => 'Méthode non autorisée'];
	redirect('home');
}
	
$email = $_POST['email'];
$pdo = Database::getPDO();
	
$authService = new AuthService($pdo);
$mailService = new MailService();
$token = $authService->setResetToken($email);

if ($token) {
    $mailService->sendResetPasswordEmail($email, $token);
}

$_SESSION['flash'] = ['type' => 'info', 'message' => 'Si cet email existe, un lien de récupération a été envoyé.'];
redirect('login-form');