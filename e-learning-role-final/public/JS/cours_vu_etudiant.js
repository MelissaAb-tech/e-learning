function toggleChapitre(index) {
    const content = document.getElementById("chapitre-content-" + index);
    content.style.display = content.style.display === "block" ? "none" : "block";
}

// Fonctions pour gérer le modal de réinitialisation
function openResetModal() {
    document.getElementById('resetModal').style.display = 'flex';
}

function closeResetModal() {
    document.getElementById('resetModal').style.display = 'none';
}

// Fonctions pour gérer le modal de feedback
function openFeedbackModal() {
    document.getElementById('feedbackModal').style.display = 'flex';
}

function closeFeedbackModal() {
    document.getElementById('feedbackModal').style.display = 'none';
}

// Fermer les modals si l'utilisateur clique en dehors
window.onclick = function (event) {
    const resetModal = document.getElementById('resetModal');
    const feedbackModal = document.getElementById('feedbackModal');

    if (event.target === resetModal) {
        closeResetModal();
    } else if (event.target === feedbackModal) {
        closeFeedbackModal();
    }
}

function openLogoutModal() {
    document.getElementById('logoutModal').style.display = 'flex';
}

function closeLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none';
}
window.onclick = function (event) {
    const resetModal = document.getElementById('resetModal');
    const feedbackModal = document.getElementById('feedbackModal');
    const logoutModal = document.getElementById('logoutModal');

    if (event.target === resetModal) {
        closeResetModal();
    } else if (event.target === feedbackModal) {
        closeFeedbackModal();
    } else if (event.target === logoutModal) {
        closeLogoutModal();
    }
}