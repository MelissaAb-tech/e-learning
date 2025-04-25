<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="/e-learning-role-final/public/style/dashboard-admin-extra.css">

<div class="admin-header">
    <a href="#" class="logout-button" onclick="openLogoutModal(); return false;">
        <i class="fas fa-sign-out-alt"></i> Déconnexion
    </a>
    <h1>Panneau d'administration</h1>
</div>

<div class="admin-main-container">

    <!-- statistiques -->
    <div class="admin-sidebar-stats">
        <h2 class="stats-title">Statistiques</h2>

        <div class="stat-widget">
            <div class="stat-icon blue">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-info">
                <h3><?= count($cours) ?></h3>
                <p>Cours disponibles</p>
            </div>
        </div>

        <div class="stat-widget">
            <div class="stat-icon green">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-info">
                <h3><?= count($etudiants) ?></h3>
                <p>Étudiants inscrits</p>
            </div>
        </div>

        <!-- Widget pour la moyenne des notes -->
        <div class="stat-widget">
            <div class="stat-icon orange">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-info">
                <h3><?= number_format($moyenneNotes, 1) ?>/5</h3>
                <p>Note moyenne (<?= $nombreFeedbacks ?> avis)</p>
            </div>
        </div>

        <!-- Bouton pour lire les feedbacks, aligné sous les statistiques -->
        <a href="/e-learning-role-final/public/admin/feedbacks" class="btn-feedbacks">
            <i class="fas fa-comments"></i> Lire les feedbacks
        </a>
    </div>

    <div class="admin-content">
        <!-- Barre de recherche avec onglets -->
        <div class="search-container">
            <div class="search-tabs">
                <div class="search-tab active" id="search-tab-all">Tout</div>
                <div class="search-tab" id="search-tab-courses">Cours</div>
                <div class="search-tab" id="search-tab-students">Étudiants</div>
            </div>
            <div class="search-input-container">
                <input type="text" id="admin-search" class="search-field" placeholder="Rechercher...">
                <div class="search-icon">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>

        <!-- Gestion des cours -->
        <div class="admin-section" id="courses-section">
            <div class="admin-section-header">
                <h2>Gestion des cours</h2>
                <a href="/e-learning-role-final/public/admin/ajouter" class="btn">Ajouter un cours</a>
            </div>

            <div class="card-grid">
                <?php foreach ($cours as $c): ?>
                    <div class="admin-card course-box course-card">
                        <a href="/e-learning-role-final/public/cours/voir/<?= $c['id'] ?>" class="course-link-overlay"></a>
                        <img src="/e-learning-role-final/public/images/<?= $c['image'] ?>" alt="Image du cours">
                        <div class="admin-card-content course-info">
                            <span class="badge"><?= $c['niveau'] ?> • <?= $c['duree'] ?></span>
                            <h3><?= $c['nom'] ?></h3>
                            <p class="prof"><?= $c['professeur'] ?></p>
                            <p><?= nl2br($c['contenu']) ?></p>
                            <div class="card-actions">
                                <a href="/e-learning-role-final/public/admin/modifier/<?= $c['id'] ?>">Modifier</a>
                                <a href="#" class="btn-supprimer-cours" data-id="<?= $c['id'] ?>">Supprimer</a>
                                <a href="/e-learning-role-final/public/admin/chapitre/ajouter/<?= $c['id'] ?>">Ajouter un chapitre</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Gestion des étudiants -->
        <div class="admin-section" id="students-section">
            <div class="admin-section-header">
                <h2>Gestion des étudiants</h2>
                <a href="/e-learning-role-final/public/admin/etudiant/ajouter" class="btn">Ajouter un étudiant</a>
            </div>

            <div class="student-grid">
                <?php foreach ($etudiants as $etudiant): ?>
                    <div class="student-card">
                        <div class="student-info">
                            <h3><?= htmlspecialchars($etudiant['nom']) ?></h3>
                            <p><?= htmlspecialchars($etudiant['email']) ?></p>
                            <div class="card-actions">
                                <a href="/e-learning-role-final/public/admin/etudiant/modifier/<?= $etudiant['id'] ?>">Modifier</a>
                                <a href="/e-learning-role-final/public/admin/etudiant/supprimer/<?= $etudiant['id'] ?>" onclick="return confirm('Supprimer cet étudiant ?')">Supprimer</a>                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</div>
<!-- Modal de confirmation pour la déconnexion admin -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <div class="modal-title">Déconnexion</div>
        <div class="modal-text">
            Êtes-vous sûr de vouloir vous déconnecter ?
        </div>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="closeLogoutModal()">
                <i class="fas fa-times"></i> Annuler
            </button>
            <a href="/e-learning-role-final/public/logout" class="modal-btn modal-btn-danger">
                <i class="fas fa-sign-out-alt"></i> Se déconnecter
            </a>
        </div>
    </div>
</div>
<!-- Modal de confirmation suppression étudiant -->
<div id="confirmDeleteModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-title">Supprimer l'étudiant</div>
        <div class="modal-text">Êtes-vous sûr de vouloir supprimer cet étudiant ?</div>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i> Annuler
            </button>
            <a id="confirmDeleteLink" href="#" class="modal-btn modal-btn-danger">
                <i class="fas fa-trash"></i> Supprimer
            </a>
        </div>
    </div>
</div>
<!-- Modal de confirmation pour suppression de cours -->
<div id="confirmDeleteCourseModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-title">Supprimer le cours</div>
        <div class="modal-text">Êtes-vous sûr de vouloir supprimer ce cours ?</div>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="closeDeleteCourseModal()">
                <i class="fas fa-times"></i> Annuler
            </button>
            <a id="confirmDeleteCourseBtn" href="#" class="modal-btn modal-btn-danger">
                <i class="fas fa-trash"></i> Supprimer
            </a>
        </div>
    </div>
</div>


<!-- Script pour la recherche en temps réel -->
<script>
 document.addEventListener('DOMContentLoaded', function() {
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
     
     // Fonction pour changer le mode de recherche
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
     
     // Fonction pour filtrer les éléments selon le terme de recherche
     function filterItems(searchTerm) {
         // Compter le nombre d'éléments visibles pour chaque section
         let visibleCourses = 0;
         let visibleStudents = 0;
         
         // Filtrer les cours si nécessaire
         if (searchMode === 'all' || searchMode === 'courses') {
             courseCards.forEach(function(card) {
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
         
         // Filtrer les étudiants si nécessaire
         if (searchMode === 'all' || searchMode === 'students') {
             studentCards.forEach(function(card) {
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
     
     // Événements
     searchInput.addEventListener('input', function() {
         filterItems(this.value.toLowerCase().trim());
     });
     
     searchTabAll.addEventListener('click', function() {
         changeSearchMode('all');
     });
     
     searchTabCourses.addEventListener('click', function() {
         changeSearchMode('courses');
     });
     
     searchTabStudents.addEventListener('click', function() {
         changeSearchMode('students');
     });
     
     // Empêcher la soumission du formulaire si l'utilisateur appuie sur Entrée
     searchInput.addEventListener('keydown', function(e) {
         if (e.key === 'Enter') {
             e.preventDefault();
         }
     });
     
     // Initialiser avec le mode "tout"
     changeSearchMode('all');
 });
 </script>

<script>
    function openLogoutModal() {
        document.getElementById('logoutModal').style.display = 'flex';
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const logoutModal = document.getElementById('logoutModal');
        if (event.target === logoutModal) {
            closeLogoutModal();
        }
    }
</script>