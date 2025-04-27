document.addEventListener('DOMContentLoaded', function () {
    // Sélectionner tous les cours et l'input de recherche
    const coursCards = document.querySelectorAll('.course-card');
    const searchInput = document.getElementById('recherche-input');

    // Détecter chaque frappe
    searchInput.addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase().trim();

        // vérifier si elle correspond à la recherche
        coursCards.forEach(function (card) {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const professor = card.querySelector('.prof').textContent.toLowerCase();

            // Si le terme de recherche est présent dans le titre OU le nom du professeur UNIQUEMENT
            if (title.includes(searchTerm) || professor.includes(searchTerm)) {
                card.style.display = ''; // Afficher la carte
            } else {
                card.style.display = 'none'; // Cacher la carte
            }
        });

        // Vérifier s'il n'y a aucun résultat pour afficher un message
        const visibleCards = document.querySelectorAll('.course-card[style="display: ;"], .course-card:not([style])');
        const noResultsElement = document.getElementById('no-results-message');

        if (visibleCards.length === 0 && searchTerm !== '') {
            // Créer le message s'il n'existe pas
            if (!noResultsElement) {
                const noResults = document.createElement('div');
                noResults.id = 'no-results-message';
                noResults.style.textAlign = 'center';
                noResults.style.padding = '40px';
                noResults.style.color = '#666';
                noResults.style.fontSize = '18px';
                noResults.style.width = '100%';
                noResults.innerHTML = '<i class="fas fa-search" style="font-size: 32px; margin-bottom: 10px; color: #ccc;"></i><br>Aucun cours ne correspond à votre recherche.';

                document.querySelector('.course-grid').appendChild(noResults);
            } else {
                noResultsElement.style.display = '';
            }
        } else if (noResultsElement) {
            noResultsElement.style.display = 'none';
        }
    });

    // Empêcher la soumission du formulaire
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });
});

function openLogoutModal() {
    document.getElementById('logoutModal').style.display = 'flex';
}

function closeLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none';
}
window.onclick = function (event) {
    const logoutModal = document.getElementById('logoutModal');
    if (event.target === logoutModal) {
        closeLogoutModal();
    }
}
// La désinscription avec confirmation
document.querySelectorAll('.btn-unsubscribe').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault(); // Empêche d'aller directement sur le lien
        const coursId = this.getAttribute('data-cours-id');

        // Modifier dynamiquement le lien du bouton 
        const confirmBtn = document.getElementById('confirmUnsubscribeLink');
        confirmBtn.href = `/e-learning-role-final/public/etudiant/desinscrire/${coursId}`;

        // Afficher le modal
        document.getElementById('confirmUnsubscribeModal').style.display = 'flex';
    });
});

//fermer le modal
function closeUnsubscribeModal() {
    document.getElementById('confirmUnsubscribeModal').style.display = 'none';
}

// Fermer le modal si on clique en dehors
window.addEventListener('click', function (event) {
    const modal = document.getElementById('confirmUnsubscribeModal');
    if (event.target === modal) {
        closeUnsubscribeModal();
    }
});
