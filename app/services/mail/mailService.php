<?php
class MailService {

	private $email;

    public function __construct() {
		$this->email = getenv('SMTP_USER') ?? 'no-reply@camagru.com';
    }

	public function sendVerificationEmail($email, $token) {
	    $subject = "Camagru - Vérifiez votre compte";
	    
	    $link = "http://localhost:8080/index.php?action=verify-email&token=" . $token;
	    
	    $message = "Bonjour,\n\nMerci de cliquer sur le lien ci-dessous pour valider votre compte :\n";
	    $message .= $link . "\n\nÀ bientôt sur Camagru !";

	    $headers = [
		    'From' => $this->email,
		    'Reply-To' => $this->email,
		    'X-Mailer' => 'PHP/' . phpversion()
		];

	    if (!mail($email, $subject, $message, $headers)) {
        	throw new Exception("L'envoi de l'email a échoué.");
    	}
	}
}