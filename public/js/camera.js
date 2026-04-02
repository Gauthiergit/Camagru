import { showToast } from './utils.js';

const initStudioCamera = () => {
	const video = document.getElementById('video');
	const errorMsg = document.getElementById('camera-error');

	const snap = document.getElementById('snap');
	const stickers = document.querySelectorAll('input[name="sticker"]');
	const canvas = document.getElementById('renderCanvas');
	const ctx = canvas.getContext('2d');
	const fileInput = document.getElementById('file-input');
	const selectFileBtn = document.getElementById('select-file-btn');

	let videoStream = null;

	let uploadedImg = null; // Contiendra l'objet Image si l'utilisateur upload

	if (!video || !snap || !canvas  || !fileInput || !selectFileBtn) {
		console.error("Elements camera int rouvables dans la page.");
		return;
	}

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
	let stickerData = {
		x: 50, y: 50,    // Position initiale
		w: 150, h: 150,  // Taille initiale
		isDragging: false,
		isResizing: false,
		dragStartX: 0, dragStartY: 0
	};
	const RESIZE_HANDLE_SIZE = 10; // Taille de la zone de redimensionnement (coin bas-droit)

	// 1. Accéder à la webcam
	if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
		// On demande uniquement la vidéo
		navigator.mediaDevices.getUserMedia({
			video: { width: 640, height: 480 },
			audio: false
		})
			.then((stream) => {
				// On injecte le flux dans l'élément vidéo
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

	// 2. Selection de fichier via bouton.
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
				uploadedImg = img; // La variable utilisée dans ta boucle drawScene
				stopCamera();      // On coupe la webcam
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
			// On arrête chaque piste (audio/vidéo) du flux
			videoStream.getTracks().forEach(track => track.stop());
			videoStream = null;
		}
	}

	function drawScene() {
		// a. Dessiner la vidéo en fond
		ctx.clearRect(0, 0, canvas.width, canvas.height);

		if (uploadedImg) {
			// On dessine l'image uploadée (en l'adaptant au format 640x480)
			const ratio = Math.min(canvas.width / uploadedImg.width, canvas.height / uploadedImg.height);
			const newWidth = uploadedImg.width * ratio;
			const newHeight = uploadedImg.height * ratio;
			const x = (canvas.width - newWidth) / 2;
			const y = (canvas.height - newHeight) / 2;
			ctx.drawImage(uploadedImg, x, y, newWidth, newHeight);
		} else if (video.srcObject) {
			// On dessine le flux webcam
			ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
		}

		// b. Dessiner le sticker si sélectionné
		if (currentStickerImg) {
			ctx.drawImage(currentStickerImg, stickerData.x, stickerData.y, stickerData.w, stickerData.h);

			// c. Dessiner le rectangle de sélection et la poignée de redimensionnement (UX)
			ctx.strokeStyle = "cyan";
			ctx.lineWidth = 2;
			ctx.strokeRect(stickerData.x, stickerData.y, stickerData.w, stickerData.h);

			// Poignée bas-droite
			ctx.fillStyle = "cyan";
			ctx.fillRect(stickerData.x + stickerData.w - RESIZE_HANDLE_SIZE, stickerData.y + stickerData.h - RESIZE_HANDLE_SIZE, RESIZE_HANDLE_SIZE, RESIZE_HANDLE_SIZE);
		}

		// Demander au navigateur de rappeler cette fonction pour la prochaine image (boucle infinie)
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

		// Le canvas d'export ne contient que la vidéo + sticker, sans cadre cyan.
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

	// Lancer la boucle de rendu meme si la webcam ne demarre pas.
	requestAnimationFrame(drawScene);

	// --- 3. SÉLECTION DU STICKER ---
	stickers.forEach(input => {
		input.addEventListener('change', (e) => {
			snap.disabled = true;
			currentStickerImg = new Image();
			currentStickerImg.onload = () => {
				snap.disabled = false;
			};
			currentStickerImg.onerror = () => {
				currentStickerImg = null;
				alert("Impossible de charger ce sticker.");
			};
			currentStickerImg.src = "/assets/stickers/" + e.target.value;
			// On réinitialise la position/taille quand on change de sticker
			stickerData.x = 50; stickerData.y = 50;
			stickerData.w = 150; stickerData.h = 150;
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

	// Vérifier si la souris est sur la poignée de redimensionnement
	function isOverResizeHandle(mx, my) {
		return mx >= (stickerData.x + stickerData.w - RESIZE_HANDLE_SIZE) &&
			mx <= (stickerData.x + stickerData.w) &&
			my >= (stickerData.y + stickerData.h - RESIZE_HANDLE_SIZE) &&
			my <= (stickerData.y + stickerData.h);
	}

	// Vérifier si la souris est à l'intérieur du sticker
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

		// Changer le curseur pour l'UX
		if (isOverResizeHandle(mouse.x, mouse.y)) {
			canvas.style.cursor = 'se-resize'; // Flèche diagonale
		} else if (isOverSticker(mouse.x, mouse.y)) {
			canvas.style.cursor = 'move'; // Croix de déplacement
		} else {
			canvas.style.cursor = 'default';
		}

		if (stickerData.isDragging) {
			stickerData.x = mouse.x - stickerData.dragStartX;
			stickerData.y = mouse.y - stickerData.dragStartY;
		} else if (stickerData.isResizing) {
			// Calculer la nouvelle taille (min 20px)
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
			alert("Le sticker est en cours de chargement, réessaie dans un instant.");
			return;
		}

		const imageData = buildImageWithoutGuides();
		if (!imageData) {
			alert("Impossible de générer l'image à envoyer.");
			return;
		}

		// MAIS ATTENTION : Pour le PHP, il faut envoyer les coordonnées relatives
		// car le PHP va refaire le montage proprement avec GD.

		const dataToSend = {
			image: imageData,
			sticker: document.querySelector('input[name="sticker"]:checked').value,
			// On envoie les coordonnées exactes choisies par l'utilisateur
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
			headers: { 'Content-Type': 'application/json' },
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
				} else {
					showToast("Erreur : " + (res?.message || "Réponse serveur invalide"), 'error');
				}
			})
			.catch((err) => {
				console.error("Upload échoué:", err);
				showToast("Erreur lors de l'envoi : " + err.message, 'error');
			});
	}
};

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initStudioCamera, { once: true });
} else {
	initStudioCamera();
}