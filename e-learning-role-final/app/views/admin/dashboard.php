<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="/e-learning-role-final/public/style/dashboard-admin-extra.css">
<link rel="stylesheet" href="/e-learning-role-final/public/style/chatbot.css">

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

        <!-- La moyenne des notes -->
        <div class="stat-widget">
            <div class="stat-icon orange">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-info">
                <h3><?= number_format($moyenneNotes, 1) ?>/5</h3>
                <p>Note moyenne (<?= $nombreFeedbacks ?> avis)</p>
            </div>
        </div>

        <!-- lire les feedbacks-->
        <a href="/e-learning-role-final/public/admin/feedbacks" class="btn-feedbacks">
            <i class="fas fa-comments"></i> Lire les feedbacks
        </a>
    </div>

    <div class="admin-content">
        <!-- barre de recherche-->
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
                                <a href="#" class="btn-supprimer-etudiant" data-id="<?= $etudiant['id'] ?>">Supprimer</a>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</div>
<!-- Confirmation pour la déconnexion admin -->
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

<!-- Confirmation pour suppression de cours -->
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
<!-- Confirmation suppression étudiant -->
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

<!-- Chatbot-->
<div id="chatbot-wrapper" class="chatbot-wrapper" data-role="admin">
    <div id="chatbot-container" class="chatbot-collapsed">
        <div class="chatbot-header">
            <div class="chatbot-title">
                <div class="chatbot-avatar"></div>
                <span>Assistant E-Learning</span>
            </div>
            <div class="chatbot-controls">
                <button id="chatbot-minimize" aria-label="Réduire">_</button>
                <button id="chatbot-close" aria-label="Fermer">x</button>
            </div>
        </div>
        <div class="chatbot-body">
            <div id="chatbot-messages"></div>
        </div>
        <div class="chatbot-suggestions" id="chatbot-suggestions"></div>
        <div class="chatbot-footer">
            <form id="chatbot-form">
                <input type="text" id="chatbot-input" placeholder="Pose ta question" autocomplete="off">
                <button type="submit" id="chatbot-submit">➤</button>
            </form>
        </div>
    </div>
    <button id="chatbot-toggle" class="chatbot-toggle" aria-label="Ouvrir l'assistant">💬</button>
</div>
<script src="/e-learning-role-final/public/JS/chatbot.js"></script>
<script src="/e-learning-role-final/public/JS/admin.js"></script>