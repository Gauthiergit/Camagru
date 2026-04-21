import { showToast, showConfirmModal } from './utils.js';

const initStudioCamera = () => {
	const video = document.getElementById('video');
	const errorMsg = document.getElementById('camera-error');
	const snap = document.getElementById('snap');
	const stickers = document.querySelectorAll('input[name="sticker"]');
	const canvas = document.getElementById('renderCanvas');
	const ctx = canvas.getContext('2d');
	const fileInput = document.getElementById('file-input');
	const selectFileBtn = document.getElementById('select-file-btn');
	const sideGallery = document.getElementById('side-gallery');
	const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	let videoStream = null;

	let uploadedImg = null;

	if (!video || !snap || !canvas || !fileInput || !selectFileBtn) {
		console.error("Elements camera introuvables dans la page.");
		return;
	}

	snap.disabled = true;

	const showCameraError = (message) => {
		if (!errorMsg) {
			console.error(message);
			return;
		}
		errorMsg.textContent = message;
		errorMsg.style.display = "block";
	};

	// --- ÉTAT DU STICKER ---
	let currentStickerImg = null;
	let selectedStickerInput = null;
	let stickerLoadToken = 0;
	let stickerData = {
		x: 50, y: 50,
		w: 150, h: 150,
		isDragging: false,
		isResizing: false,
		dragStartX: 0, dragStartY: 0
	};
	const RESIZE_HANDLE_SIZE = 10;

	if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
		navigator.mediaDevices.getUserMedia({
			video: { width: 640, height: 480 },
			audio: false
		})
			.then((stream) => {
				videoStream = stream;
				video.srcObject = stream;
				video.play();
			})
			.catch((err) => {
				console.error("Erreur : " + err);
				showCameraError("Impossible d'accéder à la caméra. Vérifie les permissions navigateur.");
			});
	} else {
		showCameraError("Votre navigateur ne supporte pas l'accès caméra.");
	}

	selectFileBtn.addEventListener('click', () => {
		fileInput.click();
	});

	fileInput.addEventListener('change', (e) => {
		const file = e.target.files?.[0];

		if (!file) {
			return;
		}

		if (!file.type.startsWith('image/')) {
			showToast("Le fichier doit etre une image.", 'error');
			fileInput.value = '';
			return;
		}

		handleFile(file);
		fileInput.value = '';
	});

	function handleFile(file) {
		const reader = new FileReader();
		reader.onerror = () => {
			showToast("Impossible de lire ce fichier.", 'error');
		};
		reader.onload = (e) => {
			const img = new Image();
			img.onload = () => {
				uploadedImg = img;
				stopCamera();
				showToast("Image chargee, ajoute un sticker puis capture.");
			};
			img.onerror = () => {
				showToast("Impossible de charger l'image.", 'error');
			};
			img.src = e.target.result;
		};
		reader.readAsDataURL(file);
	}

	function stopCamera() {
		if (videoStream) {
			videoStream.getTracks().forEach(track => track.stop());
			videoStream = null;
		}
	}

	function drawScene() {
		ctx.clearRect(0, 0, canvas.width, canvas.height);

		if (uploadedImg) {
			const ratio = Math.min(canvas.width / uploadedImg.width, canvas.height / uploadedImg.height);
			const newWidth = uploadedImg.width * ratio;
			const newHeight = uploadedImg.height * ratio;
			const x = (canvas.width - newWidth) / 2;
			const y = (canvas.height - newHeight) / 2;
			ctx.drawImage(uploadedImg, x, y, newWidth, newHeight);
		} else if (video.srcObject) {
			ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
		}

		if (currentStickerImg) {
			ctx.drawImage(currentStickerImg, stickerData.x, stickerData.y, stickerData.w, stickerData.h);

			ctx.strokeStyle = "cyan";
			ctx.lineWidth = 2;
			ctx.strokeRect(stickerData.x, stickerData.y, stickerData.w, stickerData.h);

			ctx.fillStyle = "cyan";
			ctx.fillRect(stickerData.x + stickerData.w - RESIZE_HANDLE_SIZE, stickerData.y + stickerData.h - RESIZE_HANDLE_SIZE, RESIZE_HANDLE_SIZE, RESIZE_HANDLE_SIZE);
		}

		requestAnimationFrame(drawScene);
	}

	function buildImageWithoutGuides() {
		const exportCanvas = document.createElement('canvas');
		exportCanvas.width = canvas.width;
		exportCanvas.height = canvas.height;
		const exportCtx = exportCanvas.getContext('2d');

		if (!exportCtx) {
			return null;
		}

		if (uploadedImg) {
			const ratio = Math.min(exportCanvas.width / uploadedImg.width, exportCanvas.height / uploadedImg.height);
			const newWidth = uploadedImg.width * ratio;
			const newHeight = uploadedImg.height * ratio;
			const x = (exportCanvas.width - newWidth) / 2;
			const y = (exportCanvas.height - newHeight) / 2;
			exportCtx.drawImage(uploadedImg, x, y, newWidth, newHeight);
		} else {
			exportCtx.drawImage(video, 0, 0, exportCanvas.width, exportCanvas.height);
		}
		exportCtx.drawImage(currentStickerImg, stickerData.x, stickerData.y, stickerData.w, stickerData.h);

		return exportCanvas.toDataURL('image/png');
	}

	requestAnimationFrame(drawScene);

	function resetStickerTransform() {
		stickerData.x = 50;
		stickerData.y = 50;
		stickerData.w = 150;
		stickerData.h = 150;
	}

	function clearCurrentSticker() {
		currentStickerImg = null;
		stickerData.isDragging = false;
		stickerData.isResizing = false;
		snap.disabled = true;
		canvas.style.cursor = 'default';
		selectedStickerInput = null;
	}

	// --- 3. SÉLECTION DU STICKER ---
	stickers.forEach(input => {
		input.addEventListener('click', () => {
			if (selectedStickerInput === input) {
				input.checked = false;
				clearCurrentSticker();
				return;
			}

			selectedStickerInput = input;
			const loadToken = ++stickerLoadToken;
			snap.disabled = true;
			const nextStickerImg = new Image();
			nextStickerImg.onload = () => {
				if (loadToken !== stickerLoadToken) {
					return;
				}
				currentStickerImg = nextStickerImg;
				resetStickerTransform();
				snap.disabled = false;
			};
			nextStickerImg.onerror = () => {
				if (loadToken !== stickerLoadToken) {
					return;
				}
				selectedStickerInput = null;
				input.checked = false;
				currentStickerImg = null;
				showToast("Impossible de charger ce sticker.", "error");
			};
			nextStickerImg.src = "/assets/stickers/" + input.value;
		});
	});

	// --- 4. INTERACTIVITÉ SOURIS (Drag & Resize) ---

	function getMousePos(e) {
		const rect = canvas.getBoundingClientRect();
		return {
			x: e.clientX - rect.left,
			y: e.clientY - rect.top
		};
	}

	function isOverResizeHandle(mx, my) {
		return mx >= (stickerData.x + stickerData.w - RESIZE_HANDLE_SIZE) &&
			mx <= (stickerData.x + stickerData.w) &&
			my >= (stickerData.y + stickerData.h - RESIZE_HANDLE_SIZE) &&
			my <= (stickerData.y + stickerData.h);
	}

	function isOverSticker(mx, my) {
		return mx >= stickerData.x && mx <= (stickerData.x + stickerData.w) &&
			my >= stickerData.y && my <= (stickerData.y + stickerData.h);
	}

	canvas.addEventListener('mousedown', (e) => {
		if (!currentStickerImg) return;
		const mouse = getMousePos(e);

		if (isOverResizeHandle(mouse.x, mouse.y)) {
			stickerData.isResizing = true;
		} else if (isOverSticker(mouse.x, mouse.y)) {
			stickerData.isDragging = true;
			stickerData.dragStartX = mouse.x - stickerData.x;
			stickerData.dragStartY = mouse.y - stickerData.y;
		}
	});

	canvas.addEventListener('mousemove', (e) => {
		if (!currentStickerImg) return;
		const mouse = getMousePos(e);

		if (isOverResizeHandle(mouse.x, mouse.y)) {
			canvas.style.cursor = 'se-resize';
		} else if (isOverSticker(mouse.x, mouse.y)) {
			canvas.style.cursor = 'move';
		} else {
			canvas.style.cursor = 'default';
		}

		if (stickerData.isDragging) {
			stickerData.x = mouse.x - stickerData.dragStartX;
			stickerData.y = mouse.y - stickerData.dragStartY;
		} else if (stickerData.isResizing) {
			stickerData.w = Math.max(20, mouse.x - stickerData.x);
			stickerData.h = Math.max(20, mouse.y - stickerData.y);
		}
	});

	canvas.addEventListener('mouseup', () => {
		stickerData.isDragging = false;
		stickerData.isResizing = false;
	});

	// --- 5. CAPTURE FINALE ---
	snap.addEventListener('click', () => {
		if (!currentStickerImg || !currentStickerImg.complete) {
			showToast("Le sticker est en cours de chargement, réessaie dans un instant.", 'error');
			return;
		}

		const imageData = buildImageWithoutGuides();
		if (!imageData) {
			showToast("Impossible de générer l'image à envoyer.", 'error');
			return;
		}

		const dataToSend = {
			image: imageData,
			sticker: document.querySelector('input[name="sticker"]:checked').value,
			x: stickerData.x,
			y: stickerData.y,
			w: stickerData.w,
			h: stickerData.h
		};

		sendToServer(dataToSend);
	});

	function sendToServer(imageDatas) {

		fetch('/index.php?action=upload-post', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrfToken
			},
			body: JSON.stringify(imageDatas)
		})
			.then(async (res) => {
				const data = await res.json().catch(() => null);
				if (!res.ok) {
					const message = data?.message || `Erreur HTTP ${res.status}`;
					throw new Error(message);
				}
				return data;
			})
			.then((res) => {
				if (res && res.success) {
					showToast("Photo sauvegardée !");
					const newPostHtml = `
			            <div class="side-post">
							<a href="?action=post-detail&id=${res.post_id}" class="post-link">
			                	<img src="/uploads/${res.filename}" alt="Ma photo">
							</a>
							<button class="delete-btn" onclick="deletePost(${res.post_id})">×</button>
			            </div>
			        `;
					sideGallery.insertAdjacentHTML('afterbegin', newPostHtml);
				} else {
					showToast("Erreur : " + (res?.message || "Réponse serveur invalide"), 'error');
				}
			})
			.catch((err) => {
				console.error("Upload échoué:", err);
				showToast("Erreur lors de l'envoi : " + err.message, 'error');
			});
	}

	window.deletePost = async function (postId) {
		const confirmed = await showConfirmModal(
			"Supprimer la photo",
			"Cette action est irréversible. Voulez-vous continuer ?"
		);

		if (!confirmed) return;

		fetch('/index.php?action=delete-post', {
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
					const postElement = document.getElementById(`post-${postId}`);
					if (postElement) {
						postElement.style.opacity = '0';
						postElement.style.transform = 'scale(0.8)';
						postElement.style.transition = 'all 0.3s ease';
						setTimeout(() => postElement.remove(), 300);
					}
					showToast("Photo supprimée avec succès", "success");
				} else {
					showToast(data.message, "error");
				}
			})
			.catch(err => {
				console.error(err);
				showToast("Une erreur est survenue", "error");
			});
	};
};

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initStudioCamera, { once: true });
} else {
	initStudioCamera();
}