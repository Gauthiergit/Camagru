export function showToast(message, type = 'success') {
	let container = document.getElementById('toast-container');

	if (!container) {
		container = document.createElement('div');
		container.id = 'toast-container';
		container.className = 'toast-container';
		document.body.appendChild(container);
	}

	const toast = document.createElement('div');

	toast.className = `toast ${type}`;
	toast.innerText = message;

	container.appendChild(toast);

	setTimeout(() => {
		toast.style.opacity = '0';
		toast.style.transition = 'opacity 0.5s ease';
		setTimeout(() => toast.remove(), 500);
	}, 3000);
}

export function showConfirmModal(title, message) {
    return new Promise((resolve) => {
        const modal = document.getElementById('confirm-modal');
        const btnConfirm = document.getElementById('modal-confirm');
        const btnCancel = document.getElementById('modal-cancel');
        
        document.getElementById('modal-title').innerText = title;
        document.getElementById('modal-message').innerText = message;
        
        modal.style.display = 'flex';

        const closeModal = (result) => {
            modal.style.display = 'none';
            btnConfirm.replaceWith(btnConfirm.cloneNode(true));
            btnCancel.replaceWith(btnCancel.cloneNode(true));
            resolve(result);
        };

        document.getElementById('modal-confirm').onclick = () => closeModal(true);
        document.getElementById('modal-cancel').onclick = () => closeModal(false);
    });
}