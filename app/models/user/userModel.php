<?php
class UserModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function register($username, $email, $password, $confirm) {

        if ($password !== $confirm) {
            return "Les mots de passe ne correspondent pas.";
        }

        if (strlen($password) < 8) {
            return "Le mot de passe doit faire au moins 8 caractères.";
        }

        // 2. Vérifier si l'utilisateur existe déjà
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            return "Le nom d'utilisateur ou l'email est déjà pris.";
        }
 
        // 3. Hachage et Insertion
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        try {
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $this->db->prepare($sql)->execute([$username, $email, $hashedPassword]);
            return true;
        } catch (PDOException $e) {
            return "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}