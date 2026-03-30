<div class="profile-container">
	<h2>Changer le mot de passe</h2>
    <form action="/index.php?action=reset-password" method="POST">
		<input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="password" name="password" placeholder="Nouveau mot de passe" required>
        <input type="password" name="confirm_password" placeholder="Confirmer le nouveau" required>
        <button type="submit" name="reset_password">Changer le mot de passe</button>
    </form>
</div>