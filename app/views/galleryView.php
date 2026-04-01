<div class="gallery-container">
    <h2>Galerie Camagru</h2>

	<?php if (empty($posts)): ?>
		<div>
			<p>"Aucune photo pour le moment, sois le premier à en prendre une !"</p>
		</div>
	<?php endif; ?>

    <div class="photo-grid">
        <?php foreach ($posts as $post): ?>
            <div class="photo-item">
                <img src="/uploads/<?php echo $post['filename']; ?>" alt="Photo Camagru">
                </div>
        <?php endforeach; ?>
    </div>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="/index.php?action=gallery&page=<?php echo $page - 1; ?>" class="btn">Précédent</a>
        <?php endif; ?>

        <span>Page <?php echo $page; ?> sur <?php echo $totalPages; ?></span>

        <?php if ($page < $totalPages): ?>
            <a href="/index.php?action=gallery&page=<?php echo $page + 1; ?>" class="btn">Suivant</a>
        <?php endif; ?>
    </div>
</div>