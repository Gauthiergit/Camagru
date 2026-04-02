<div class="gallery-container">
	<div id="toast-container" class="toast-container"></div>
    <h2>Galerie Camagru</h2>

	<?php if (empty($posts)): ?>
		<div>
			<p>"Aucune photo pour le moment, sois le premier à en prendre une !"</p>
		</div>
	<?php endif; ?>

    <div class="photo-grid">
        <?php foreach ($posts as $post): ?>
		    <div class="photo-card">
		        <img src="/uploads/<?= $post['filename'] ?>" class="main-img">
		        
		        <div class="actions">
		            <button class="like-btn" data-id="<?= $post['id'] ?>">
			            <span class="heart-icon"><?= $post['user_has_liked'] > 0 ? '❤️' : '🤍' ?></span> 
			            <span class="like-count"><?= $post['likes_count'] ?></span>
			        </button>
		        </div>

		        <div class="comments-section">
		            <div id="comments-list-<?= $post['id'] ?>">
						<?php foreach ($post['comments_list'] as $comment): ?>
						    <p><strong><?= htmlspecialchars($comment['username']) ?></strong> : <?= htmlspecialchars($comment['content']) ?></p>
						<?php endforeach; ?>
                	</div> 
		            <?php if (isset($_SESSION['user_id'])): ?>
		                <form action="/index.php?action=comment" method="POST" class="comment-form">
		                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
		                    <input type="text" name="content" placeholder="Ajouter un commentaire..." required>
		                    <button type="submit">Envoyer</button>
		                </form>
		            <?php endif; ?>
		        </div>
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

<script type="module" src="/js/gallery.js"></script>