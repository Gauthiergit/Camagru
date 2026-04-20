<div class="auth-container">
    <h2>Connexion</h2>
    
    <form action="/index.php?action=login" method="POST">
        <div class="form-group">
            <label for="login">Nom d'utilisateur ou Email</label>
            <input type="text" name="login" id="login" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <div class="password-field">
                <input type="password" name="password" id="password" autocomplete="current-password" required>
                <button
                    type="button"
                    class="password-toggle-btn"
                    data-toggle-password
                    data-target="password"
                    aria-label="Afficher le mot de passe"
                    aria-controls="password"
                    aria-pressed="false"
                >
					<i class="fa-solid fa-eye-slash"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-primary">Se connecter</button>
    </form>
    
    <p class="auth-switch">Pas encore de compte ? <a href="/index.php?action=register-form">Inscrivez-vous</a></p>
    <a class="forget-switch" href="/index.php?action=forget-password-form">Mot de passe oublié ?</a>
</div>

<script type="module" src="/js/auth.js"></script>