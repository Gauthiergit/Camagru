export function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    
    toast.className = `toast ${type}`;
    toast.innerText = message;
    
    container.appendChild(toast);

    // On retire le toast après 3 secondes
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.5s ease';
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}