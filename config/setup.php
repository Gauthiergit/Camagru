<?php
require_once('database.php');

try {
    // Connexion directe à la DB (Postgres la crée via le docker-compose au démarrage)
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création de la table users (Syntaxe Postgres : SERIAL pour l'auto-increment)
    $usersTable = "CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        token VARCHAR(255),
		reset_token VARCHAR(255) DEFAULT NULL,
        is_verified BOOLEAN DEFAULT FALSE
    )";
    $pdo->exec($usersTable);

	$postsTable = "CREATE TABLE IF NOT EXISTS posts (
	    id SERIAL PRIMARY KEY,
	    user_id INT NOT NULL,
	    filename VARCHAR(255) NOT NULL,
		likes_count INT NOT NULL DEFAULT 0,
	    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
	)";
	$pdo->exec($postsTable);

	$likesTable = "CREATE TABLE IF NOT EXISTS likes (
	    id SERIAL PRIMARY KEY,
	    user_id INT NOT NULL,
	    post_id INT NOT NULL,
	    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
	    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
	    UNIQUE (user_id, post_id)
	)";
	$pdo->exec($likesTable);

	$commentsTable = "CREATE TABLE IF NOT EXISTS comments (
	    id SERIAL PRIMARY KEY,
	    user_id INT NOT NULL,
	    post_id INT NOT NULL,
	    content TEXT NOT NULL,
	    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
	    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
	)";
	$pdo->exec($commentsTable);

    echo "Félicitations ! Docker est lié et les tables sont prêtes sur Postgres.";

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>