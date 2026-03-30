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

	public function resetPassword($userId, $newPassword) {
		if (!$this->isValidPassword($newPassword)) {
	        return "Le mot de passe doit contenir au minimum 8 caractères, un chiffre, une majuscule, une minuscule et un caractère spécial.";
	    }
	    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
	    $resetRequest = $this->db->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE id = ?");
	    $resetRequest->execute([$hashedPassword, $userId]);
		return true;
	}

	public function setResetToken($email) {
	    $token = bin2hex(random_bytes(32));
	    $resetRequest = $this->db->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
	    return $resetRequest->execute([$token, $email]) ? $token : false;
	}

	public function isValidPassword($password) {
	    // Explication de la regex :
	    // ^               : Début de la chaîne
	    // (?=.*[a-z])     : Au moins une minuscule
	    // (?=.*[A-Z])     : Au moins une majuscule
	    // (?=.*[0-9])     : Au moins un chiffre (optionnel mais conseillé)
	    // (?=.*[!@#$%^&*]) : Au moins un caractère spécial parmi cette liste
	    // .{8,}           : Au moins 8 caractères au total
	    // $               : Fin de la chaîne
	    
	    $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/';
	    
	    return preg_match($regex, $password);
	}
}