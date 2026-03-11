<?php
require_once('database.php');

try {
    // Connexion directe à la DB (Postgres la crée via le docker-compose au démarrage)
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création de la table users (Syntaxe Postgres : SERIAL pour l'auto-increment)
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        token VARCHAR(255),
        is_verified BOOLEAN DEFAULT FALSE
    )";
    
    $pdo->exec($sql);
    echo "Félicitations ! Docker est lié et la table 'users' est prête sur Postgres.";

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>