<?php
if (!defined('ROOT')) die('Accès direct interdit');

/**
 * Redirige l'utilisateur vers une action spécifique
 */
function redirect(string $action): void {
    header("Location: /index.php?action=" . $action);
    exit();
}