<div class="gallery-container">
	<div class="gallery-hero">
		<div>
			<span class="section-badge">Galerie</span>
			<h2>Galerie Camagru</h2>
			<p>Parcourez les créations de la communauté et aimez vos montages préférés.</p>
		</div>
	</div>

	<?php if (empty($posts)): ?>
		<div class="gallery-empty">
			<p>Aucune photo pour le moment, sois le premier à en prendre une !</p>
		</div>
	<?php endif; ?>

    <div class="photo-grid">
        <?php foreach ($posts as $post): ?>
		    <div class="photo-card">
				<a href="?action=post-detail&id=<?= $post['id'] ?>" class="post-link">
					<img src="/uploads/<?= $post['filename'] ?>" class="gallery-img">
				</a>
		        <div class="actions">
		            <button class="like-btn" data-id="<?= $post['id'] ?>">
						<?php if($post['user_has_liked']): ?>
							<i class="fa-solid fa-heart"></i>
						<?php else: ?>
							<i class="fa-regular fa-heart"></i>		
						<?php endif;?>
			            <span class="like-count"><?= $post['likes_count'] ?></span>
			        </button>
		        </div>

		        <div class="comments-section">
		            <div id="comments-list-<?= $post['id'] ?>">
						<?php foreach ($post['comments_list'] as $comment): ?>
						    <p><strong><?= $comment['username'] ?></strong> : 
							<?= $comment['content'] ?></p>
						<?php endforeach; ?>
                	</div>
		        </div>
		    </div>
		<?php endforeach; ?>
    </div>

    <div class="pagination">
        <?php if ($page > 1): ?>
			<a href="/index.php?action=gallery&page=<?php echo $page - 1; ?>" class="pagination-btn">Précédent</a>
        <?php endif; ?>

		<span class="pagination-info">Page <?php echo $page; ?> sur <?php echo $totalPages; ?></span>

        <?php if ($page < $totalPages): ?>
			<a href="/index.php?action=gallery&page=<?php echo $page + 1; ?>" class="pagination-btn">Suivant</a>
        <?php endif; ?>
    </div>
</div>

<script type="module" src="/js/gallery.js"></script>