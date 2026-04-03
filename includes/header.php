<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru - 42</title>
    <link rel="stylesheet" href="/css/style.css">
	<script src="https://kit.fontawesome.com/66ee37b62d.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <nav>
            <a href="index.php" class="logo">
				<img src="/assets/logo/logo.png" width="30" height="30" alt="Camagru">
				<p>CAMAGRU</p>
			</a>
            <div class="menu">
                <a href="index.php">Accueil</a>
                <a href="/index.php?action=gallery">Galerie</a>
				<?php if (isset($_SESSION['user_id'])): ?>
		            <a href="/index.php?action=profile">Mon Profil</a>
		            <span class="user-greeting"><strong>🟢<?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
		            <form action="/index.php?action=logout" method="POST" class="logout-form">
				        <button type="submit" class="btn-logout-link">Déconnexion</button>
				    </form>
		        <?php else: ?>
		            <a href="/index.php?action=register-form">S'inscrire</a>
		            <a href="/index.php?action=login-form" class="btn-login">Connexion</a>
		        <?php endif; ?>
            </div>
        </nav>
    </header>

    <main>

	<?php if (isset($_SESSION['flash'])): ?>
	    <div class="alert alert-<?php echo $_SESSION['flash']['type'];?>">
	        <?php echo htmlspecialchars($_SESSION['flash']['message']);?>
	    </div>
	    <?php unset($_SESSION['flash']); ?>
	<?php endif; ?>