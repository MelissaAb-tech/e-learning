
let currentDeleteType = '';
let currentDeleteId = null;

// Initialiser les conteneurs
document.addEventListener('DOMContentLoaded', function () {
    addPdfField();
    addYoutubeField();
    addVideoField();

    // Initialiser la visibilité des boutons dans chaque conteneur
    updateRemoveButtonsVisibility(document.getElementById('pdf-container'));
    updateRemoveButtonsVisibility(document.getElementById('youtube-container'));
    updateRemoveButtonsVisibility(document.getElementById('video-container'));
});

function addPdfField() {
    const container = document.getElementById('pdf-container');
    const card = document.createElement('div');
    card.className = 'file-input-card';

    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'pdfs[]';
    input.accept = 'application/pdf';

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'remove-file-btn';
    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
    removeBtn.onclick = function () { removeCard(this); };

    card.appendChild(input);
    card.appendChild(removeBtn);
    container.appendChild(card);
    updateRemoveButtonsVisibility(container);
}

function addYoutubeField() {
    const container = document.getElementById('youtube-container');
    const card = document.createElement('div');
    card.className = 'file-input-card';

    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'youtube_links[]';
    input.placeholder = 'https://www.youtube.com/watch?v=...';
    input.className = 'youtube-input';

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'remove-file-btn';
    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
    removeBtn.onclick = function () { removeCard(this); };

    card.appendChild(input);
    card.appendChild(removeBtn);
    container.appendChild(card);
    updateRemoveButtonsVisibility(container);
}

function addVideoField() {
    const container = document.getElementById('video-container');
    const card = document.createElement('div');
    card.className = 'file-input-card';

    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'videos[]';
    input.accept = 'video/mp4';

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'remove-file-btn';
    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
    removeBtn.onclick = function () { removeCard(this); };

    card.appendChild(input);
    card.appendChild(removeBtn);
    container.appendChild(card);
    updateRemoveButtonsVisibility(container);
}

function removeCard(button) {
    const card = button.parentElement;
    const container = card.parentElement;

    // Ne pas supprimer 
    if (container.children.length > 1) {
        container.removeChild(card);
        updateRemoveButtonsVisibility(container);
    }
}

// mettre à jour la visibilité des boutons de suppression
function updateRemoveButtonsVisibility(container) {
    const cards = container.querySelectorAll('.file-input-card');
    const removeButtons = container.querySelectorAll('.remove-file-btn');

    // Si on a plus d'un élément afficher tous les boutons de suppression
    if (cards.length > 1) {
        removeButtons.forEach(btn => {
            btn.style.visibility = 'visible';
        });
    } else {
        // masquer tous les boutons de suppression
        removeButtons.forEach(btn => {
            btn.style.visibility = 'hidden';
        });
    }
}

// Ouvrir le modal de confirmation
function confirmDelete(type, id) {
    // Mémoriser le type et l'ID pour l'action de suppression
    currentDeleteType = type;
    currentDeleteId = id;

    // Afficher le modal
    document.getElementById('delete-modal').style.display = 'flex';

    // Configurer le bouton de confirmation
    document.getElementById('confirm-delete-btn').onclick = function () {
        markForDeletion(currentDeleteType, currentDeleteId);
        closeDeleteModal();
    };
}

// Fermer le modal
function closeDeleteModal() {
    document.getElementById('delete-modal').style.display = 'none';
}

// Marquer un fichier comme à supprimer
function markForDeletion(type, id) {
    // Mettre à jour le champ caché pour indiquer que ce fichier doit être supprimé
    const keepField = document.getElementById(`keep-${type}-${id}`);
    if (keepField) {
        keepField.value = "0";
    }

    // Ajouter une classe visuelle pour indiquer que le fichier sera supprimé
    const fileCard = document.getElementById(`${type}-${id}`);
    if (fileCard) {
        fileCard.classList.add('marked-for-deletion');

        // Ajouter un bouton pour annuler la suppression
        const fileActions = fileCard.querySelector('.file-actions');

        // Vérifier si un bouton d'annulation existe déjà
        const existingUndoBtn = fileCard.querySelector('.undo-delete-btn');
        if (!existingUndoBtn) {
            const undoBtn = document.createElement('button');
            undoBtn.type = 'button';
            undoBtn.className = 'undo-delete-btn';
            undoBtn.innerHTML = '<i class="fas fa-undo"></i>';
            undoBtn.title = 'Annuler la suppression';
            undoBtn.onclick = function () {
                undoMarkForDeletion(type, id);
            };

            fileActions.appendChild(undoBtn);
        }
    }
}

// Annuler le marquage pour suppression
function undoMarkForDeletion(type, id) {
    // Réinitialiser le champ caché
    const keepField = document.getElementById(`keep-${type}-${id}`);
    if (keepField) {
        keepField.value = "1";
    }

    // Retirer la classe visuelle
    const fileCard = document.getElementById(`${type}-${id}`);
    if (fileCard) {
        fileCard.classList.remove('marked-for-deletion');

        // Supprimer le bouton d'annulation
        const undoBtn = fileCard.querySelector('.undo-delete-btn');
        if (undoBtn) {
            undoBtn.remove();
        }
    }
}

// Fermer le modal si l'utilisateur clique en dehors
window.onclick = function (event) {
    const modal = document.getElementById('delete-modal');
    if (event.target === modal) {
        closeDeleteModal();
    }
};