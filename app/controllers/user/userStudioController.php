<?php
// On vérifie si l'utilisateur est connecté (sécurité supplémentaire)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Veuillez vous connecter.'];
    redirect('login-form');
    exit;
}

// Ici, on pourra plus tard charger la liste des stickers depuis la DB ou un dossier
$stickers = ['sticker1.png', 'sticker2.png', 'sticker3.png'];

// On charge la vue
require_once ROOT . '/includes/header.php';
require_once ROOT . '/app/views/user/userStudioView.php';
require_once ROOT . '/includes/footer.php';