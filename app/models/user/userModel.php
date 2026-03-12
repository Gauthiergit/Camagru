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
        $dbRequest = $this->db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $dbRequest->execute([$username, $email]);
        if ($dbRequest->fetch()) {
            return "Le nom d'utilisateur ou l'email est déjà pris.";
        }
 
        // 3. Hachage et Insertion
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        try {
            $insertRequest = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $insertRequest->execute([$username, $email, $hashedPassword]);
            return true;
        } catch (PDOException $e) {
            return "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }

	public function login($login, $password) {
		$dbRequest = $this->db->prepare("SELECT	* FROM users WHERE username = ? OR email = ?");
		$dbRequest->execute([$login, $login]);
		$user = $dbRequest->fetch(PDO::FETCH_ASSOC);

	    if (!$user) {
	        return "Identifiants invalides.";
	    }

	    if (!password_verify($password, $user['password'])) {
	        return "Identifiants invalides.";
	    }

		// if ($user['is_verified'] === false) {
	    //     return "Veuillez confirmer votre compte par email avant de vous connecter.";
	    // }

		return $user;
	}

	public function logout() {
	    $_SESSION = [];

	    if (ini_get("session.use_cookies")) {
	        $params = session_get_cookie_params();
	        setcookie(session_name(), '', time() - 42000,
	            $params["path"], $params["domain"],
	            $params["secure"], $params["httponly"]
	        );
	    }

	    session_destroy();
	}
}