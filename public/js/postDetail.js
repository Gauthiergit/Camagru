import { showToast } from './utils.js';

// Scroll auto vers le bas au chargement
const container = document.getElementById('comments-container');
if (container) {
	container.scrollTop = container.scrollHeight;
}

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