<?php
require_once ROOT . "/app/services/mail/mailService.php";
require_once ROOT . "/app/services/auth/authService.php";

class UserService {
    private $db;
	private $mailService;
	private $authService;

    public function __construct($pdo) {
        $this->db = $pdo;
		$this->mailService = new MailService();
		$this->authService = new AuthService($pdo);
    }

    public function register($username, $email, $password, $confirm) {

        if ($password !== $confirm) {
            return "Les mots de passe ne correspondent pas.";
        }

        if (!$this->authService->isValidPassword($password)) {
	        return "Le mot de passe doit contenir au minimum 8 caractères, un chiffre, une majuscule, une minuscule et un caractère spécial.";
	    }

        $dbRequest = $this->db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $dbRequest->execute([$username, $email]);
        if ($dbRequest->fetch()) {
            return "Le nom d'utilisateur ou l'email est déjà pris.";
        }
 
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

		$token = bin2hex(random_bytes(16));
        
        try {
            $insertRequest = $this->db->prepare("INSERT INTO users (username, email, password, token) VALUES (?, ?, ?, ?)");
            $insertRequest->execute([$username, $email, $hashedPassword, $token]);
			$this->mailService->sendVerificationEmail($email, $token);
            return true;
        } catch (PDOException $e) {
            return "Erreur lors de l'enregistrement : " . $e->getMessage();
        } catch (Exception $mailError) {
			return "Erreur lors de l'envoie de l'email: " . $mailError->getMessage();
		}
    }

	public function getUserByResetToken($token) {
	    $dbRequest = $this->db->prepare("SELECT id FROM users WHERE reset_token = ?");
	    $dbRequest->execute([$token]);
	    return $dbRequest->fetch();
	}

	public function getUserById($id) {
	    $dbRequest = $this->db->prepare("SELECT id, username, email, is_verified, wants_notifs FROM users WHERE id = ?");
	    $dbRequest->execute([$id]);
	    return $dbRequest->fetch(PDO::FETCH_ASSOC);
	}

	public function getUserPasswordHash($id) {
		$dbRequest = $this->db->prepare("SELECT password FROM users WHERE id = ?");
		$dbRequest->execute([$id]);
		return $dbRequest->fetch(PDO::FETCH_ASSOC)['password'];
	}

	public function updateUsername($id, $username) {
	    $selectRequest = $this->db->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
	    $selectRequest->execute([$username, $id]);
	    if ($selectRequest->fetch()) {
	        return "Ce pseudo est déjà utilisé.";
	    }

	    $updateRequest = $this->db->prepare("UPDATE users SET username = ? WHERE id = ?");
	    return $updateRequest->execute([$username, $id]) ? true : "Erreur lors de la mise à jour.";
	}

	public function updateEmail($userId, $newEmail, $password) {
	    $userPassword = $this->getUserPasswordHash($userId);
		$user = $this->getUserById($userId);

		if (!password_verify($password, $userPassword)) {
			return "Non autorisé";
		}
		
		 $checkRequest = $this->db->prepare("SELECT id FROM users WHERE email = ?");
		 $checkRequest->execute([$newEmail]);
		 if ($checkRequest->fetch()) {
            return "L'email est déjà pris.";
        }


	    if ($user['email'] !== $newEmail) {
	        $newToken = bin2hex(random_bytes(16));
	        $updateRequest = $this->db->prepare("UPDATE users SET email = ?, is_verified = false, token = ? WHERE id = ?");
	        $updateRequest->execute([$newEmail, $newToken, $userId]);
	        
	        try {
				$this->mailService->sendVerificationEmail($newEmail, $newToken);
			} catch (Exception $mailError) {
				return "Erreur lors de l'envoie de l'email: " . $mailError->getMessage();
			}
	    }
	    return true;
	}

	public function updatePassword($id, $oldPassword, $newPassword) {
	    $userPassword = $this->getUserPasswordHash($id);

	    if (!password_verify($oldPassword, $userPassword)) {
	        return "L'ancien mot de passe est incorrect.";
	    }

		if (!$this->authService->isValidPassword($newPassword)) {
	        return "Le mot de passe doit contenir au minimum 8 caractères, un chiffre, une majuscule, une minuscule et un caractère spécial.";
	    }

	    $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
	    $updateRequest = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
	    return $updateRequest->execute([$newHash, $id]) ? true : "Erreur lors du changement de mot de passe.";
	}

	public function updateNotificationSettings($userId, $wantsNotifs) {
	    $dbRequest = $this->db->prepare("UPDATE users SET wants_notifs = ? WHERE id = ?");
	    return $dbRequest->execute([$wantsNotifs ? 1 : 0, $userId]);
	}
}