<?php
header('Content-Type: application/json; charset=utf-8');
require_once ROOT . "/app/services/post/postService.php";
require_once ROOT . "/app/services/user/userService.php";
require_once ROOT . "/app/services/mail/mailService.php";
require_once ROOT . '/app/core/database.php';

if (!isset($_SESSION['user_id'])) {
	http_response_code(401);
	echo json_encode(['success' => false, 'message' => 'Non autorisé']);
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
	$postService->addComment($_SESSION['user_id'], $data['post_id'], $data['content']);
	$currentUser = $userSevice->getUserById($_SESSION['user_id']);
	$ownerId = $postService->getPostOwnerId($data['post_id']);

	if (!$ownerId) {
		http_response_code(404);
		echo json_encode([
			'success' => false,
			'message' => 'Publication introuvable'
		]);
		exit;
	}

	$owner = $userSevice->getUserById($ownerId);
	$mailService->sendNotification($owner['email'], $owner['wants_notifs']);
	echo json_encode([
		'success' => true,
		'username' => $currentUser['username'],
		'content' => $data['content']
	]);
} catch (Throwable $e) {
	http_response_code(500);
	echo json_encode([
		'success' => false,
		'message' => "Erreur serveur pendant l'ajout du commentaire"
	]);
}

