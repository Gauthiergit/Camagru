<?php

class Database {
    private static $pdo = null;

    public static function getPDO() {
        if (self::$pdo=== null) {
            require ROOT . '/config/database.php';

            try {
                self::$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}