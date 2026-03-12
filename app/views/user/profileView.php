<div class="profile-container">
    <h2>Mon Profil</h2>

    <section class="profile-info">
        <h3>Mes informations personnelles</h3>
        <p><strong>Nom d'utilisateur :</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Statut du compte :</strong> 
            <?php echo $user['is_verified'] ? 
				'<span class="status verified">Vérifié ✅</span>' :
				'<span class="status unverified">Non vérifié ❌</span>';
			?>
        </p>
    </section>

    <hr>

    <section class="profile-actions">
        <h3>Paramètres du compte</h3>
        <div class="profile-container">
		    <section>
		        <h3>Modifier mon nom d'utilisateur</h3>
		        <form action="/index.php?action=update-profile" method="POST">
		            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
		            <button type="submit" name="update_username">Changer mon nom d'utilisateur</button>
		        </form>
		    </section>

		    <hr>

			<section>
		        <h3>Modifier mon email</h3>
		        <form action="/index.php?action=update-profile" method="POST">
		            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
					<input type="password" name="password" placeholder="Mot de passe" required>
		            <button type="submit" name="update_email">Changer mon email</button>
		        </form>
		    </section>

		    <hr>

		    <section>
		        <h3>Changer le mot de passe</h3>
		        <form action="/index.php?action=update-profile" method="POST">
		            <input type="password" name="old_password" placeholder="Ancien mot de passe" required>
		            <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>
		            <input type="password" name="confirm_password" placeholder="Confirmer le nouveau" required>
		            <button type="submit" name="update_password">Changer le mot de passe</button>
		        </form>
		    </section>
		</div>
    </section>
</div>