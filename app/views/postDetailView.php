<div class="post-detail-container">
    <div class="post-image-side">
        <img src="/uploads/<?= $post['filename'] ?>" alt="Photo de <?= $post['author'] ?>">
    </div>

    <div class="post-info-side">
        <div class="post-header">
            <strong>@<?= $post['author'] ?></strong>
            <span class="date"><?= date('d/m/Y', strtotime($post['created_at'])) ?></span>
        </div>

        <div class="comments-scroll-area" id="comments-container">
            <?php if (empty($post['comments_list'])): ?>
                <p class="no-comments">Aucun commentaire pour le moment.</p>
            <?php else: ?>
                <?php foreach ($post['comments_list'] as $comment): ?>
                    <div class="comment" id="comment-<?= $comment['comment_id']?>">
                        <strong><?= $comment['username'] ?></strong>
                        <p><?= $comment['content'] ?></p>
						<?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']): ?>
							<button class="delete-comment" onclick="deleteComment(<?= $comment['comment_id'] ?>)">x</button>
						<?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="post-footer">
            <div class="actions">
                <button class="like-btn <?= $post['user_has_liked'] ? 'active' : '' ?>" data-id="<?= $post['id'] ?>">
                    <?php if($post['user_has_liked']): ?>
						<i class="fa-solid fa-heart"></i>
					<?php else: ?>
						<i class="fa-regular fa-heart"></i>		
					<?php endif;?>
                    <span class="like-count"><?= $post['likes_count'] ?></span>
                </button>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form id="comment-form" class="comment-form">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <textarea name="content" placeholder="Ajouter un commentaire..." required></textarea>
                    <button type="submit">Publier</button>
                </form>
            <?php else: ?>
                <p><a href="?action=login-form">Connectez-vous</a> pour liker ou commenter.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script type="module" src="/js/postDetail.js"></script>