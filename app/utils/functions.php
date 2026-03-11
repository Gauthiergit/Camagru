<?php
if (!defined('ROOT')) die('Accès direct interdit');

/**
 * Redirige l'utilisateur vers une action spécifique
 */
function redirect(string $page): void {
    header("Location: /index.php?page=" . $page);
    exit();
}