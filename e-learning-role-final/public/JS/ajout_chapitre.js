// Initialiser les conteneurs
document.addEventListener('DOMContentLoaded', function () {
    addPdfField();
    addYoutubeField();
    addVideoField();
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

    // Mettre à jour la visibilité des boutons 
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

    // Mettre à jour la visibilité des boutons
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

// Mettre à jour la visibilité des boutons de suppression
function updateRemoveButtonsVisibility(container) {
    const cards = container.querySelectorAll('.file-input-card');
    const removeButtons = container.querySelectorAll('.remove-file-btn');

    // afficher tous les boutons de suppression
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