<?php
header('Content-Type: application/json; charset=utf-8');
require_once ROOT . "/app/services/post/postService.php";
require_once ROOT . "/app/services/user/userService.php";
require_once ROOT . "/app/services/mail/mailService.php";
require_once ROOT . '/app/core/database.php';

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
	echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
	exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!is_array($data)) {
	http_response_code(400);
	echo json_encode(['success' => false, 'message' => 'Données invalides']);
	exit;
}

$pdo = Database::getPDO();

$postService = new PostService($pdo);
$userSevice = new UserService($pdo);
$mailService = new MailService();

try {
	$ownerId = $postService->getPostOwnerId($data['post_id']);

	if (!$ownerId) {
		http_response_code(404);
		echo json_encode([
			'success' => false,
			'message' => 'Publication introuvable'
		]);
		exit;
	}

	$comment = $postService->addComment($_SESSION['user_id'], $data['post_id'], $data['content']);
	if (!$comment) {
		throw new RuntimeException('Impossible d\'ajouter le commentaire');
	}

	$currentUser = $userSevice->getUserById($_SESSION['user_id']);
	$owner = $userSevice->getUserById($ownerId);
	$mailService->sendNotification($owner['email'], $owner['wants_notifs']);
	echo json_encode([
		'success' => true,
			'comment_id' => $comment['id'],
			'username' => $currentUser['username'],
			'content' => $comment['content']
	]);
} catch (Throwable $e) {
	http_response_code(500);
	echo json_encode([
		'success' => false,
		'message' => "Erreur serveur pendant l'ajout du commentaire"
	]);
}

