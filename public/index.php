<?php
define('ROOT', dirname(__DIR__));
session_start();

$page = $_GET['page'] ?? 'home';

require_once ROOT . "/app/utils/functions.php";

require_once ROOT . '/includes/header.php';

switch ($page) {
    case 'home':
        require_once ROOT . '/app/views/homeView.php';
        break;
    case 'register':
        require_once ROOT . '/app/views/auth/registerView.php';
        break;
    default:
        http_response_code(404);
        echo "<h1>404 - Page non trouvée</h1>";
        break;
}

require_once ROOT . '/includes/footer.php';