document.addEventListener('DOMContentLoaded', () => {
	const toggleButtons = document.querySelectorAll('[data-toggle-password]');

	toggleButtons.forEach((toggleButton) => {
		const targetId = toggleButton.getAttribute('data-target');

		if (!targetId) {
			return;
		}

		const passwordInput = document.getElementById(targetId);

		if (!passwordInput) {
			return;
		}

		const visibleIcon = toggleButton.querySelector('.fa-solid');

		toggleButton.addEventListener('click', () => {
			const isHidden = passwordInput.type === 'password';
			passwordInput.type = isHidden ? 'text' : 'password';

			if (visibleIcon) {
				visibleIcon.classList.toggle('fa-eye', isHidden);
				visibleIcon.classList.toggle('fa-eye-slash', !isHidden);
			}

			toggleButton.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
			toggleButton.setAttribute('aria-label', isHidden ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
		});
	});
});