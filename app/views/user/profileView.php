<div class="profile-page">
	<div class="profile-hero">
		<span class="section-badge">Mon espace</span>
		<h2>Mon Profil</h2>
		<p>Gérez vos informations, votre sécurité et vos préférences en un seul endroit.</p>
	</div>

	<div class="profile-grid">
		<section class="profile-card profile-info">
			<h3>Mes informations personnelles</h3>

			<p><strong>Nom d'utilisateur :</strong> <?php echo htmlspecialchars($user['username']); ?></p>
			<p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
			<p><strong>Statut du compte :</strong>
				<?php echo $user['is_verified']
					? '<span class="status verified">Vérifié</span>'
					: '<span class="status unverified">Non vérifié</span>';
				?>
			</p>
		</section>

		<section class="profile-card profile-actions">
			<h3>Paramètres du compte</h3>

			<section class="profile-action-block">
				<h4>Modifier mon nom d'utilisateur</h4>
				<form action="/index.php?action=update-profile" method="POST" class="profile-form">
					<input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
					<button type="submit" name="update_username" class="btn-primary profile-submit">Changer mon nom d'utilisateur</button>
				</form>
			</section>

			<section class="profile-action-block">
				<h4>Modifier mon email</h4>
				<form action="/index.php?action=update-profile" method="POST" class="profile-form">
					<input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
					<div class="password-field">
		                <input type="password" placeholder="Mot de passe" name="password" id="password" autocomplete="current-password" required>
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
					<button type="submit" name="update_email" class="btn-primary profile-submit">Changer mon email</button>
				</form>
			</section>

			<section class="profile-action-block">
				<h4>Changer le mot de passe</h4>
				<form action="/index.php?action=update-profile" method="POST" class="profile-form">
					<div class="password-field">
		                <input type="password" placeholder="Ancien mot de passe" name="old_password" id="old_password" autocomplete="old_password" required>
		                <button
		                    type="button"
		                    class="password-toggle-btn"
		                    data-toggle-password
		                    data-target="old_password"
		                    aria-label="Afficher le mot de passe"
		                    aria-controls="old_password"
		                    aria-pressed="false"
		                >
							<i class="fa-solid fa-eye-slash"></i>
		                </button>
		            </div>
					<div class="password-field">
		                <input type="password" placeholder="Nouveau mot de passe" name="new_password" id="new_password" autocomplete="new_password" required>
		                <button
		                    type="button"
		                    class="password-toggle-btn"
		                    data-toggle-password
		                    data-target="new_password"
		                    aria-label="Afficher le mot de passe"
		                    aria-controls="new_password"
		                    aria-pressed="false"
		                >
							<i class="fa-solid fa-eye-slash"></i>
		                </button>
		            </div>
					<div class="password-field">
		                <input type="password" placeholder="Confirmer le mot de passe" name="confirm_password" id="confirm_password" autocomplete="confirm_password" required>
		                <button
		                    type="button"
		                    class="password-toggle-btn"
		                    data-toggle-password
		                    data-target="confirm_password"
		                    aria-label="Afficher le mot de passe"
		                    aria-controls="confirm_password"
		                    aria-pressed="false"
		                >
							<i class="fa-solid fa-eye-slash"></i>
		                </button>
		            </div>
					<button type="submit" name="update_password" class="btn-primary profile-submit">Changer le mot de passe</button>
				</form>
			</section>

			<section class="profile-action-block">
				<h4>Préférences</h4>
				<div class="settings-item">
					<label for="notif-toggle">Recevoir des notifications par email lors d'un nouveau commentaire</label>
					<label class="switch">
						<input type="checkbox" id="notif-toggle" <?= $user['wants_notifs'] ? 'checked' : '' ?>>
						<span class="slider round"></span>
					</label>
				</div>
			</section>
		</section>
	</div>
</div>

<script type="module" src="/js/settings.js"></script>
<script type="module" src="/js/auth.js"></script>