<?php
define('ROOT', dirname(__DIR__));
session_start();

require_once ROOT . "/app/utils/functions.php";

$action = $_GET['action'] ?? 'home';

$logicRoutes = [
    'register' => '/app/controllers/auth/registerController.php',
	'login' => '/app/controllers/auth/loginController.php',
	'logout' => '/app/controllers/auth/logoutController.php',
    'setup'    => '/config/setup.php',
];

$viewRoutes = [
    'home'          => '/app/views/homeView.php',
    'register-form' => '/app/views/auth/registerView.php',
	'login-form' => '/app/views/auth/loginView.php'
];

// ------Logic------
if (array_key_exists($action, $logicRoutes)) {
    require_once ROOT . $logicRoutes[$action];
    exit();
}

// ------Views------
if (array_key_exists($action, $viewRoutes)) {
    require_once ROOT . '/includes/header.php';
    require_once ROOT . $viewRoutes[$action];
    require_once ROOT . '/includes/footer.php';
} else {
    http_response_code(404);
    require_once ROOT . '/includes/header.php';
    echo "<h1>404 - Page non trouvée</h1>";
    require_once ROOT . '/includes/footer.php';
}