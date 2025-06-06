document.addEventListener('DOMContentLoaded', function () {
    // Éléments DOM
    const searchInput = document.getElementById('admin-search');
    const courseCards = document.querySelectorAll('.course-card');
    const studentCards = document.querySelectorAll('.student-card');
    const searchTabAll = document.getElementById('search-tab-all');
    const searchTabCourses = document.getElementById('search-tab-courses');
    const searchTabStudents = document.getElementById('search-tab-students');
    const coursesSection = document.getElementById('courses-section');
    const studentsSection = document.getElementById('students-section');

    // Mode de recherche (all, courses, students)
    let searchMode = 'all';

    // changer le mode de recherche
    function changeSearchMode(mode) {
        searchMode = mode;

        // Mise à jour des onglets actifs
        searchTabAll.classList.toggle('active', mode === 'all');
        searchTabCourses.classList.toggle('active', mode === 'courses');
        searchTabStudents.classList.toggle('active', mode === 'students');

        // Affichage conditionnel des sections
        if (mode === 'all') {
            coursesSection.style.display = '';
            studentsSection.style.display = '';
        } else if (mode === 'courses') {
            coursesSection.style.display = '';
            studentsSection.style.display = 'none';
        } else if (mode === 'students') {
            coursesSection.style.display = 'none';
            studentsSection.style.display = '';
        }

        // Relancer la recherche avec le terme actuel
        filterItems(searchInput.value.toLowerCase().trim());
    }

    // Filtrer les éléments selon le terme de recherche
    function filterItems(searchTerm) {
        let visibleCourses = 0;
        let visibleStudents = 0;

        // Filtrer les cours si nécessaire
        if (searchMode === 'all' || searchMode === 'courses') {
            courseCards.forEach(function (card) {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const professor = card.querySelector('.prof').textContent.toLowerCase();

                if (title.includes(searchTerm) || professor.includes(searchTerm)) {
                    card.style.display = '';
                    visibleCourses++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Afficher message si aucun cours trouvé
            const noCoursesMessage = document.getElementById('no-courses-message');
            if (visibleCourses === 0 && searchTerm !== '') {
                if (!noCoursesMessage) {
                    const noResults = document.createElement('div');
                    noResults.id = 'no-courses-message';
                    noResults.style.textAlign = 'center';
                    noResults.style.padding = '20px';
                    noResults.style.color = '#666';
                    noResults.style.width = '100%';
                    noResults.innerHTML = '<i class="fas fa-search"></i> Aucun cours trouvé';
                    document.querySelector('.card-grid').appendChild(noResults);
                } else {
                    noCoursesMessage.style.display = '';
                }
            } else if (noCoursesMessage) {
                noCoursesMessage.style.display = 'none';
            }
        }

        // Filtrer les étudiants 
        if (searchMode === 'all' || searchMode === 'students') {
            studentCards.forEach(function (card) {
                const name = card.querySelector('h3').textContent.toLowerCase();
                const email = card.querySelector('p').textContent.toLowerCase();

                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    card.style.display = '';
                    visibleStudents++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Afficher message si aucun étudiant trouvé
            const noStudentsMessage = document.getElementById('no-students-message');
            if (visibleStudents === 0 && searchTerm !== '') {
                if (!noStudentsMessage) {
                    const noResults = document.createElement('div');
                    noResults.id = 'no-students-message';
                    noResults.style.textAlign = 'center';
                    noResults.style.padding = '20px';
                    noResults.style.color = '#666';
                    noResults.style.width = '100%';
                    noResults.innerHTML = '<i class="fas fa-search"></i> Aucun étudiant trouvé';
                    document.querySelector('.student-grid').appendChild(noResults);
                } else {
                    noStudentsMessage.style.display = '';
                }
            } else if (noStudentsMessage) {
                noStudentsMessage.style.display = 'none';
            }
        }
    }

    searchInput.addEventListener('input', function () {
        filterItems(this.value.toLowerCase().trim());
    });

    searchTabAll.addEventListener('click', function () {
        changeSearchMode('all');
    });

    searchTabCourses.addEventListener('click', function () {
        changeSearchMode('courses');
    });

    searchTabStudents.addEventListener('click', function () {
        changeSearchMode('students');
    });

    // Empecher la soumission du formulaire 
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });

    // Initialiser avec le mode "tout"
    changeSearchMode('all');
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
// suppression de cours avec confirmation
document.querySelectorAll('.btn-supprimer-cours').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault(); // Bloquer l'action normale
        const coursId = this.getAttribute('data-id');

        // Ouvrir le modal
        openDeleteCourseModal(coursId);
    });
});

function openDeleteCourseModal(coursId) {
    const modal = document.getElementById('confirmDeleteCourseModal');
    const confirmBtn = document.getElementById('confirmDeleteCourseBtn');

    // Mettre à jour le lien du bouton "Supprimer" dans le modal
    confirmBtn.href = `/e-learning-role-final/public/admin/supprimer/${coursId}`;

    modal.style.display = 'flex'; // Afficher le modal
}

function closeDeleteCourseModal() {
    const modal = document.getElementById('confirmDeleteCourseModal');
    modal.style.display = 'none'; // Cacher le modal
}

// Fermer le modal si on clique en dehors de la boîte
window.addEventListener('click', function (event) {
    const modal = document.getElementById('confirmDeleteCourseModal');
    if (event.target === modal) {
        closeDeleteCourseModal();
    }
});
// Gestion de la suppression des étudiants 
document.querySelectorAll('.btn-supprimer-etudiant').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault(); // Empêche le lien de s'ouvrir directement

        const etudiantId = this.getAttribute('data-id');

        // Mettre à jour le lien "Supprimer"
        const confirmBtn = document.getElementById('confirmDeleteLink');
        confirmBtn.href = `/e-learning-role-final/public/admin/etudiant/supprimer/${etudiantId}`;

        // Ouvrir le modal
        document.getElementById('confirmDeleteModal').style.display = 'flex';
    });
});

//fermer la modale de suppression étudiant
function closeDeleteModal() {
    document.getElementById('confirmDeleteModal').style.display = 'none';
}

// Fermer la modale si on clique en dehors de la fenêtre
window.addEventListener('click', function (event) {
    const modal = document.getElementById('confirmDeleteModal');
    if (event.target === modal) {
        closeDeleteModal();
    }
});

