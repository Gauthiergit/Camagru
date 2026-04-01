<?php

require_once ROOT . '/app/core/database.php';
require_once ROOT . '/app/services/post/postService.php';

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

$postService = new PostService(Database::getPDO());
$posts = $postService->getPaginatedPosts($limit, $offset);
$totalPosts = $postService->getTotalPostsCount();

$totalPages = ceil($totalPosts / $limit);

require_once ROOT . '/includes/header.php';
require_once ROOT . '/app/views/galleryView.php';
require_once ROOT . '/includes/footer.php';