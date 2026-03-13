<?php
if (!defined('ROOT')) die('Accès direct interdit');

function redirect(string $action): void {
    header("Location: /index.php?action=" . $action);
    exit();
}