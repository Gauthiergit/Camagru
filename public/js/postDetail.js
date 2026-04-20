import { showToast } from './utils.js';

// Scroll auto vers le bas au chargement
const container = document.getElementById('comments-container');
if (container) {
	container.scrollTop = container.scrollHeight;
}

document.addEventListener('DOMContentLoaded', () => {

	document.addEventListener('click', (e) => {
		const likeBtn = e.target.closest('.like-btn');
		if (!likeBtn) return;
		const postId = likeBtn.getAttribute('data-id');
		const countSpan = likeBtn.querySelector('.like-count');
		const likeIcon = likeBtn.querySelector('.fa-heart')

		fetch('/index.php?action=like', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({ post_id: postId })
		})
			.then(res => res.json())
			.then(data => {
				if (data.success) {
					if (data.status === 'liked') {
						likeIcon.classList.remove("fa-regular");
						likeIcon.classList.add("fa-solid");
						countSpan.innerText = parseInt(countSpan.innerText) + 1;
					} else {
						likeIcon.classList.remove("fa-solid");
						likeIcon.classList.add("fa-regular");
						countSpan.innerText = parseInt(countSpan.innerText) - 1;
					}
				} else {
					showToast(data.message, "error");
				}
			})
			.catch(err => console.error("Erreur Like:", err));
	});
});

document.getElementById('comment-form')?.addEventListener('submit', function (e) {
	e.preventDefault();
	const formData = new FormData(this);
	const data = Object.fromEntries(formData.entries());

	fetch('/index.php?action=comment', {
		method: 'POST',
		headers: { 'Content-Type': 'application/json' },
		body: JSON.stringify(data)
	})
		.then(res => res.json())
		.then(res => {
			if (res.success) {
				if (container) {
					const noComment = container.querySelector('.no-comments');
					if (noComment)
						container.removeChild(noComment);
					const comment = document.createElement('div');
					comment.className = 'comment';

					const username = document.createElement('strong');
					username.textContent = res.username;

					const content = document.createElement('p');
					content.textContent = res.content;

					comment.append(username, content);
					container.appendChild(comment);
					container.scrollTop = container.scrollHeight;
				}
				this.reset();
			} else {
				showToast(res.message || 'Impossible d\'ajouter le commentaire.', 'error');
			}
		})
		.catch(() => {
			showToast('Erreur réseau pendant l\'envoi du commentaire.', 'error');
		});
});