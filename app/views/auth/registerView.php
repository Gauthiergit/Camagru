<div class="auth-container">
    <h2>Créer un compte</h2>
    <form action="/index.php?action=register" method="POST">
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
            <div class="password-field">
                <input type="password" name="password" id="password" autocomplete="new-password" required>
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

        <div class="form-group">
            <label for="password_confirm">Confirmer le mot de passe</label>
            <div class="password-field">
                <input type="password" name="password_confirm" id="password_confirm" autocomplete="new-password" required>
                <button
                    type="button"
                    class="password-toggle-btn"
                    data-toggle-password
                    data-target="password_confirm"
                    aria-label="Afficher le mot de passe"
                    aria-controls="password_confirm"
                    aria-pressed="false"
                >
                    <i class="fa-solid fa-eye-slash"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-primary">S'inscrire</button>
    </form>

    <p class="auth-switch">Déjà un compte ? <a href="/index.php?action=login-form">Connectez-vous</a></p>
</div>

<script type="module" src="/js/auth.js"></script>