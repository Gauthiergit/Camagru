<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru - 42</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php" class="logo">📷 CAMAGRU</a>
            <div class="menu">
                <a href="index.php">Accueil</a>
                <a href="gallery.php">Galerie</a>
				<?php if (isset($_SESSION['user_id'])): ?>
		            <span class="user-greeting">Salut, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> !</span>
		            <a href="/index.php?action=profile">Mon Profil</a>
		            <form action="/index.php?action=logout" method="POST" style="display: inline;">
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