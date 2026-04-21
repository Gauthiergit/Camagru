import { showToast } from './utils.js';

document.addEventListener('DOMContentLoaded', () => {

	const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	document.addEventListener('click', (e) => {
		const commentsToggleBtn = e.target.closest('.comments-toggle-btn');
		if (commentsToggleBtn) {
			const card = commentsToggleBtn.closest('.photo-card');
			const commentsSection = card?.querySelector('.comments-section');

			if (!commentsSection) {
				return;
			}

			commentsSection.classList.toggle('is-collapsed');
			const isExpanded = !commentsSection.classList.contains('is-collapsed');
			commentsToggleBtn.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
			return;
		}

		const btn = e.target.closest('.like-btn');
		if (!btn) return;

		const postId = btn.getAttribute('data-id');
		const countSpan = btn.querySelector('.like-count');
		const likeIcon = btn.querySelector('.fa-heart')

		fetch('/index.php?action=like', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrfToken
			},
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