<?php

require_once ROOT . "/app/services/user/userService.php";
require_once ROOT . '/app/core/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	$_SESSION['flash'] = ['type' => 'danger', 'message' => 'Méthode non autorisée'];
	redirect('home');
}

$pdo = Database::getPDO();

$userService = new UserService($pdo);

$result = $userService->register(
    $_POST['username'],
    $_POST['email'],
    $_POST['password'],
    $_POST['password_confirm']
);

if ($result === true) {
    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Compte créé ! Veuillez vérifier vos e-mails'];
    redirect("login-form");
} else {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => $result];
    redirect("register-form");
}
