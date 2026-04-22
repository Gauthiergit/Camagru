<div class="auth-container reset-password-container">
    <span class="section-badge">Sécurité</span>
    <h2>Changer le mot de passe</h2>
    <p class="auth-description">Choisis un nouveau mot de passe pour sécuriser ton compte.</p>

    <form action="/index.php?action=reset-password" method="POST" class="profile-form">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
		<div class="password-field">
            <input type="password" placeholder="Nouveau mot de passe" name="password" id="password" autocomplete="current-password" required>
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
		<div class="password-field">
            <input type="password" placeholder="Confirmer le mot de passe" name="password_confirm" id="password_confirm" autocomplete="password_confirm" required>
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
        <button type="submit" name="reset_password" class="btn-primary profile-submit">Changer le mot de passe</button>
    </form>
</div>

<script type="module" src="/js/auth.js"></script>