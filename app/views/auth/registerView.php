<div class="auth-container">
    <h2>Créer un compte</h2>

	<?php if (isset($_SESSION['flash'])): ?>
	    <div class="alert alert-<?php echo $_SESSION['flash']['type'];?>">
	        <?php echo htmlspecialchars($_SESSION['flash']['message']);?>
	    </div>
	    <?php unset($_SESSION['flash']); // On vide le casier pour que l'erreur disparaisse au prochain refresh ?>
	<?php endif; ?>

    <form action="register_action.php" method="POST">
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirm">Confirmer le mot de passe</label>
            <input type="password" name="password_confirm" id="password_confirm" required>
        </div>

        <button type="submit" class="btn-primary">S'inscrire</button>
    </form>
    
    <p>Déjà un compte ? <a href="login.php">Connectez-vous</a></p>
</div>