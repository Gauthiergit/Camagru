<div class="auth-container">
    <h2>Connexion</h2>
    
    <form action="/index.php?action=login" method="POST">
        <div class="form-group">
            <label for="login">Nom d'utilisateur ou Email</label>
            <input type="text" name="login" id="login" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit" class="btn-primary">Se connecter</button>
    </form>
    
    <p>Pas encore de compte ? <a href="/index.php?action=register-form">Inscrivez-vous</a></p>
</div>