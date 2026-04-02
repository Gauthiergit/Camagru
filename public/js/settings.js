import { showToast } from './utils.js';

document.addEventListener('DOMContentLoaded', () => {
	const toggle = document.getElementById('notif-toggle');

	if (!toggle) {
		return;
	}

	toggle.addEventListener('change', async (e) => {
		const isChecked = e.target.checked;
		const previousState = !isChecked;

		toggle.disabled = true;

		try {
			const res = await fetch('/index.php?action=update-notifs', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ wants_notifs: isChecked })
			});

			let data = null;
			const contentType = res.headers.get('content-type') || '';

			if (contentType.includes('application/json')) {
				data = await res.json();
			}

			if (!res.ok || (data && data.success === false)) {
				toggle.checked = previousState;
				showToast('Erreur : ' + (data?.message || 'Impossible de mettre a jour les preferences'), 'error');
				return;
			}

			showToast('Preferences mises a jour !', 'success');
		} catch (err) {
			toggle.checked = previousState;
			showToast('Erreur de connexion', 'error');
		} finally {
			toggle.disabled = false;
		}
	});
});