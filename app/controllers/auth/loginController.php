<?php
require_once ROOT . "/app/services/auth/authService.php";
require_once ROOT . '/app/core/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $pdo = Database::getPDO();

    $authService = new AuthService($pdo);

    $result = $authService->login(
        $_POST['login'],
        $_POST['password'],
    );

    if (is_array($result)) {
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['username'] = $result['username'];
        
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Ravi de vous revoir, ' . $result['username'] . ' !'];
        redirect('home');
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => $result];
        redirect('login-form');
    }
}