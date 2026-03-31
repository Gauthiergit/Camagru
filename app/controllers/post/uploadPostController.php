<?php
header('Content-Type: application/json; charset=utf-8');

require_once ROOT . '/app/core/database.php';
require_once ROOT . '/app/services/post/postService.php';

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

try {
	$pdo = Database::getPDO();
	$postService = new PostService($pdo);
	$postService->registerPost($data);
} catch (Throwable $e) {
	http_response_code(500);
	echo json_encode([
		'success' => false,
		'message' => 'Erreur serveur pendant la sauvegarde de la photo.'
	]);
}