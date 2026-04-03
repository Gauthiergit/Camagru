<div class="auth-container reset-password-container">
    <span class="section-badge">Sécurité</span>
    <h2>Changer le mot de passe</h2>
    <p class="auth-description">Choisis un nouveau mot de passe pour sécuriser ton compte.</p>

    <form action="/index.php?action=reset-password" method="POST" class="profile-form">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="password" name="password" placeholder="Nouveau mot de passe" required>
        <input type="password" name="confirm_password" placeholder="Confirmer le nouveau" required>
        <button type="submit" name="reset_password" class="btn-primary profile-submit">Changer le mot de passe</button>
    </form>
</div>