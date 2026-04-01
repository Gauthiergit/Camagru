import { showToast } from './utils.js';

document.addEventListener('DOMContentLoaded', () => {
    
    // On écoute les clics sur le document, mais on ne traite que les .like-btn
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.like-btn');
        if (!btn) return;

        const postId = btn.getAttribute('data-id');
        const countSpan = btn.querySelector('.like-count');
        const heartIcon = btn.querySelector('.heart-icon');

        // 1. Envoyer la requête au serveur
        fetch('/index.php?action=like', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ post_id: postId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // 2. Mettre à jour l'interface (UI)
                // data.action peut être 'liked' ou 'unliked' selon ton PHP
                if (data.status === 'liked') {
                    btn.classList.add('active');
                    heartIcon.innerText = '❤️';
                    countSpan.innerText = parseInt(countSpan.innerText) + 1;
                } else {
                    btn.classList.remove('active');
                    heartIcon.innerText = '🤍';
                    countSpan.innerText = parseInt(countSpan.innerText) - 1;
                }
            } else {
                showToast(data.message, "error");
            }
        })
        .catch(err => console.error("Erreur Like:", err));
    });
});