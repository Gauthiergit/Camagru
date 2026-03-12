<?php
require_once ROOT . "/app/models/user/userModel.php";
require_once ROOT . '/app/core/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = Database::getPDO();
    $userModel = new UserModel($db);
    $userId = $_SESSION['user_id'];

    if (isset($_POST['update_username'])) {
        $result = $userModel->updateUsername($userId, $_POST['username']);
        if ($result === true) {
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Username mis à jour !'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => $result];
        }
    }

	if (isset($_POST['update_email'])) {
        $result = $userModel->updateEmail($userId, $_POST['email'], $_POST['password']);
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
            $result = $userModel->updatePassword($userId, $_POST['old_password'], $_POST['new_password']);
            if ($result === true) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Mot de passe modifié avec succès !'];
            } else {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => $result];
            }
        }
    }

    redirect('profile');
}