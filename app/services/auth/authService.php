<?php
class AuthService {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
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

		if ($user['is_verified'] === false) {
	        return "Veuillez confirmer votre compte par email avant de vous connecter.";
	    }

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

	public function verifyEmail($token) {
		$dbRequest = $this->db->prepare("SELECT id FROM users WHERE token = ?");
		$dbRequest->execute([$token]);
		$user = $dbRequest->fetch();

		if ($user) {
		    $dbRequest = $this->db->prepare("UPDATE users SET is_verified = true, token = NULL WHERE id = ?");
		    $dbRequest->execute([$user['id']]);
		 	return true;
		}
		return false;
	}
}