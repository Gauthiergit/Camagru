<div class="auth-container">
    <h2>Mot de passe oublié</h2>
    
    <form action="/index.php?action=forget-password" method="POST">
        <div class="form-group">
            <label for="email">Entrez votre Email</label>
            <input type="text" name="email" id="email" required>
        </div>

        <button type="submit" class="btn-primary">Envoyer</button>
    </form>
</div>