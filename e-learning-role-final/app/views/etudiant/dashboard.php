<link rel="stylesheet" href="/e-learning-role-final/public/style/etudiant-dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/e-learning-role-final/public/style/dashboard-etudiant-extra.css">

<div class="student-header">
    <div class="header-top">
        <a href="#" class="logout-button" onclick="openLogoutModal(); return false;">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>

        <a href="/e-learning-role-final/public/etudiant/mon-compte" class="account-button">
            <i class="fas fa-user"></i> Mon compte
        </a>
    </div>
    <h1>Bienvenue sur votre espace étudiant</h1>
    <div class="search-and-description" style="display: flex; align-items: center; gap: 20px; margin-top: 15px;">

        <p>Accédez à vos cours disponibles et suivez votre progression</p>
    </div>
</div>

<!-- Affichage du message de succès s'il existe -->
<?php if (isset($_SESSION['feedback_success'])): ?>
    <div class="feedback-success">
        <i class="fas fa-check-circle"></i>
        <?= $_SESSION['feedback_success'] ?>
    </div>
    <?php
    // Suppression du message après affichage pour qu'il ne s'affiche qu'une fois
    unset($_SESSION['feedback_success']);
?>
<?php endif; ?>

<div class="search-wrapper">
    <input type="text" id="recherche-input" name="recherche" placeholder="Rechercher un cours..." class="search-field">
    <span class="search-icon"><i class="fas fa-search"></i></span>
</div>

<div class="course-grid">

    <?php foreach ($cours as $c): ?>
        <div class="course-card">
            <img src="/e-learning-role-final/public/images/<?= $c['image'] ?>" alt="Image du cours">
            <div class="course-details">
                <span class="course-badge"><?= $c['niveau'] ?> • <?= $c['duree'] ?></span>
                <h3><?= $c['nom'] ?></h3>
                <p class="prof">Par <?= $c['professeur'] ?></p>
                <p class="desc"><?= nl2br($c['contenu']) ?></p>

                <?php if (isset($coursInscrits) && isset($coursInscrits[$c['id']]) && $coursInscrits[$c['id']]): ?>
                    <!-- L'étudiant est inscrit -->
                    <div class="course-actions">
                        <a href="/e-learning-role-final/public/cours/voir/<?= $c['id'] ?>" class="btn-access">
                            <i class="fas fa-book-open"></i> Accéder
                        </a>
                        <a href="/e-learning-role-final/public/etudiant/desinscrire/<?= $c['id'] ?>" class="btn-unsubscribe"
                            onclick="return confirm('Êtes-vous sûr de vouloir vous désinscrire de ce cours ?')">
                            <i class="fas fa-user-minus"></i> Se désinscrire
                        </a>
                    </div>
                <?php else: ?>
                    <!-- L'étudiant n'est pas inscrit -->
                    <div class="course-actions">
                        <a href="/e-learning-role-final/public/etudiant/inscrire/<?= $c['id'] ?>" class="btn-subscribe">
                            <i class="fas fa-user-plus"></i> S'inscrire au cours
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="section-divider">
    <h2>Exprimez-vous</h2>
    <p>Vos retours sont précieux pour améliorer la plateforme</p>
</div>

<!-- Feedback -->
<div class="student-feedback-box">
    <form method="POST" action="/e-learning-role-final/public/etudiant/feedback" class="feedback-form">
        <div class="form-group">
            <label for="rating">Note globale :</label>
            <div class="star-rating">
                <input type="radio" name="rating" id="rating-5" value="5"><label for="rating-5">&#9733;</label>
                <input type="radio" name="rating" id="rating-4" value="4"><label for="rating-4">&#9733;</label>
                <input type="radio" name="rating" id="rating-3" value="3"><label for="rating-3">&#9733;</label>
                <input type="radio" name="rating" id="rating-2" value="2"><label for="rating-2">&#9733;</label>
                <input type="radio" name="rating" id="rating-1" value="1"><label for="rating-1">&#9733;</label>
            </div>
        </div>


        <div class="form-group">
            <label for="commentaire">Commentaire :</label>
            <textarea name="commentaire" id="commentaire" rows="4" placeholder="Exprimez-vous librement..."
                required></textarea>
        </div>

        <button type="submit" class="btn">Envoyer mon avis</button>
    </form>
</div>
<!-- Modal de confirmation pour la déconnexion -->
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sélectionner tous les cours et l'input de recherche
        const coursCards = document.querySelectorAll('.course-card');
        const searchInput = document.getElementById('recherche-input');

        // Ajouter un événement sur l'input pour détecter chaque frappe
        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase().trim();

            // Parcourir chaque carte de cours et vérifier si elle correspond à la recherche
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

        // Empêcher la soumission du formulaire si l'utilisateur appuie sur Entrée
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
</script>