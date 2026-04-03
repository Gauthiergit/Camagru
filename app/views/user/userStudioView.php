<div class="camera-container">
	<h2>Studio Photo</h2>
	<p id="camera-error" class="alert alert-danger" style="display:none; margin-bottom: 10px;"></p>
	<div class="camera-layout">
		<div class="camera-main">
			<div class="main-capture" style="position:relative; width:640px; max-width:100%;">
				<video id="video" width="640" height="480" autoplay playsinline style="display:none";></video>
				<canvas id="renderCanvas" width="640" height="480" style="border:1px solid black; cursor:move;"></canvas>
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
	
			<div class="controls">
				<input id="file-input" type="file" accept="image/*" style="display:none;">
				<button id="select-file-btn" class="btn-primary" type="button">Choisir une image</button>
				<button id="snap" class="btn-primary" disabled>2. Prendre la photo</button>
			</div>
		</div>

		<aside class="camera-sidebar">
	        <h3>Mes dernières photos</h3>
	        <div id="side-gallery" class="side-gallery">
	            <?php foreach ($userPosts as $post): ?>
	                <div class="side-post" id="post-<?= $post['id'] ?>">
	                    <img src="/uploads/<?= $post['filename'] ?>" alt="Ma photo">
	                    <button class="delete-btn" onclick="deletePost(<?= $post['id'] ?>)">×</button>
	                </div>
	            <?php endforeach; ?>
	        </div>
	    </aside>
	</div>
</div>

<script type="module" src="/js/camera.js"></script>