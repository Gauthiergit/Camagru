<?php
require_once ROOT . "/app/services/user/userService.php";
require_once ROOT . '/app/core/database.php';

if (!isset($_SESSION['user_id'])) {
   $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Non connecté'];
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	$_SESSION['flash'] = ['type' => 'danger', 'message' => 'Méthode non autorisée'];
	redirect('studio');
}

$db = Database::getPDO();
$userService = new UserService($db);
$userId = $_SESSION['user_id'];
$token = $_POST['csrf_token'] ?? '';

if (!hash_equals($_SESSION['csrf_token'], $token)) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Non autorisé'];
	exit;
}

if (isset($_POST['update_username'])) {
    $result = $userService->updateUsername($userId, $_POST['username']);
    if ($result === true) {
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['flash'] = ['type' => 'success', 'message' => "Nom d'utilisateur mis à jour !"];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => $result];
    }
}

if (isset($_POST['update_email'])) {
    $result = $userService->updateEmail($userId, $_POST['email'], $_POST['password']);
    if ($result === true) {
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Email modifié. Veuillez-vérifier vos mails !'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => $result];
    }
}

if (isset($_POST['update_password'])) {
    if ($_POST['new_password'] !== $_POST['confirm_password']) {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Les nouveaux mots de passe ne correspondent pas.'];
    } else {
        $result = $userService->updatePassword($userId, $_POST['old_password'], $_POST['new_password']);
        if ($result === true) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Mot de passe modifié avec succès !'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => $result];
        }
    }
}

redirect('profile');