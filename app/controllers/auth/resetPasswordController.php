<?php
require_once ROOT . "/app/services/user/userService.php";
require_once ROOT . "/app/services/auth/authService.php";
require_once ROOT . '/app/core/database.php';

$pdo = Database::getPDO();
$token = $_GET['token'] ?? $_POST['token'] ?? null;
if (!$token) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Non autorisé'];
    redirect('studio');
}

$userService = new UserService($pdo);
$user = $userService->getUserByResetToken($token);

if (!$user) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Lien invalide ou expiré.'];
    redirect('login-form');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['password'] !== $_POST['password_confirm'])
	{
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Les mots de passe ne correspondent pas.'];
	} else {
		$authService = new AuthService($pdo);
		$result = $authService->resetPassword($user['id'], $_POST['password']);
		if ($result === true){
			$_SESSION['flash'] = ['type' => 'success', 'message' => 'Mot de passe modifié ! Connectez-vous.'];
			redirect('login-form');
			exit;
		} else {
			$_SESSION['flash'] = ['type' => 'danger', 'message' => $result];
		}
	}
}

require_once ROOT . '/includes/header.php';
require_once ROOT . '/app/views/auth/resetPasswordView.php';
require_once ROOT . '/includes/footer.php';