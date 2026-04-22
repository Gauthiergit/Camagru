<div class="studio-page">
	<div class="studio-hero">
		<span class="section-badge">Studio</span>
		<h2>Studio Photo</h2>
		<p>Crée ta photo, ajoute un sticker et publie ton montage en quelques clics.</p>
	</div>

	<p id="camera-error" class="alert alert-danger studio-error" style="display:none;"></p>

	<div class="camera-layout">
		<div class="camera-main studio-card">
			<div class="main-capture">
				<video id="video" width="640" height="480" autoplay playsinline style="display:none;"></video>
				<canvas id="renderCanvas" width="640" height="480" style="border:1px solid black; cursor:move;"></canvas>
			</div>

			<div class="controls studio-controls">
				<input id="file-input" type="file" accept="image/*" style="display:none;">
				<button id="select-file-btn" class="btn-primary" type="button">Choisir une image</button>
				<button id="camera" class="btn-primary" type="button">Utiliser ma camera</button>
				<button id="snap" class="btn-primary" disabled>📸 Prendre la photo</button>
			</div>
	
			<div class="stickers-selector">
				<h3>1. Choisis ton accessoire</h3>
				<div class="stickers-grid">
					<?php foreach($stickers as $sticker): ?>
						<label>
							<input type="radio" name="sticker" value="<?= htmlspecialchars($sticker, ENT_QUOTES, 'UTF-8') ?>">
							<img src="/assets/stickers/<?= htmlspecialchars($sticker, ENT_QUOTES, 'UTF-8') ?>" width="100" alt="Sticker">
						</label>
					<?php endforeach; ?>
				</div>
			</div>	
		</div>

		<aside class="camera-sidebar studio-card">
	        <h3>Mes dernières photos</h3>
	        <div id="side-gallery" class="side-gallery">
	            <?php foreach ($userPosts as $post): ?>
	                <div class="side-post" id="post-<?= $post['id'] ?>">
						<a href="?action=post-detail&id=<?= $post['id'] ?>" class="post-link">
							<img src="/uploads/<?= $post['filename'] ?>" alt="Ma photo">
						</a>
	                    <button class="delete-btn" onclick="deletePost(<?= $post['id'] ?>)">×</button>
	                </div>
	            <?php endforeach; ?>
	        </div>
	    </aside>
	</div>
</div>

<script type="module" src="/js/camera.js"></script>