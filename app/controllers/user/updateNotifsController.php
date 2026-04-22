<?php
require_once ROOT . "/app/services/user/userService.php";
require_once ROOT . '/app/core/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non connecté']);
    exit;
}

$headers = getallheaders();
$isAjax = isset($headers['X-CSRF-TOKEN']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
if (!isset($headers['X-CSRF-TOKEN']) || !hash_equals($_SESSION['csrf_token'], $headers['X-CSRF-TOKEN'])) {
    if ($isAjax) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Non autorisée']);
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Non autorisé.'];
        header('Location: index.php?action=studio');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Methode non autorisee']);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!is_array($data) || !array_key_exists('wants_notifs', $data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Payload invalide']);
    exit;
}

$userService = new UserService(Database::getPDO());
$wantsNotifs = filter_var($data['wants_notifs'], FILTER_VALIDATE_BOOLEAN);
$success = $userService->updateNotificationSettings($_SESSION['user_id'], $wantsNotifs);

if ($success) {
    echo json_encode(['success' => true, 'wants_notifs' => $wantsNotifs]);
    exit;
}

http_response_code(500);
echo json_encode(['success' => false, 'message' => 'Erreur DB']);
exit;
