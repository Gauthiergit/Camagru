<?php
session_start();
require_once "config/database.php";
require_once "app/models/user/userModel.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // On crée la connexion PDO
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // On instancie notre "Model"
    $userModel = new UserModel($pdo);

    // On appelle la méthode magique
    $result = $userModel->register(
        $_POST['username'],
        $_POST['email'],
        $_POST['password'],
        $_POST['password_confirm']
    );

    if ($result === true) {
        // Succès : Redirection vers le login
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Compte créé !'];
        redirect("login");
    } else {
        // Échec : On récupère le message d'erreur renvoyé par le Model
        $_SESSION['flash'] = ['type' => 'danger', 'message' => $result];
        redirect("register");
    }
    exit();
}