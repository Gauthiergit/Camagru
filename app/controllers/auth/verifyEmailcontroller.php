<?php
require_once ROOT . "/app/services/auth/authService.php";
require_once ROOT . '/app/core/database.php';

$token = $_GET['token'] ?? null;

if (!$token) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Token manquant.'];
    redirect('home');
}

$pdo = Database::getPDO();

$authService = new AuthService($pdo);

$result = $authService->verifyEmail($token);

if ($result) {
    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Votre compte est maintenant vérifié !'];
} else {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Token invalide ou déjà utilisé.'];
}
redirect('home');