<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    .stat-icon.orange {
        background-color: #F97316;
    }
    
    .btn-feedbacks {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background-color: #3B82F6;
        color: white;
        padding: 12px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        margin: 15px 0 5px 0;
        transition: background-color 0.3s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .btn-feedbacks:hover {
        background-color: #2563EB;
    }
    
    .btn-feedbacks i {
        font-size: 16px;
    }
</style>

<div class="admin-header">
    <a href="/e-learning-role-final/public/logout" class="logout-button">
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

        <!-- Gestion des cours -->
        <div class="admin-section">
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
                                <a href="/e-learning-role-final/public/admin/supprimer/<?= $c['id'] ?>" onclick="return confirm('Supprimer ce cours ?')">Supprimer</a>
                                <a href="/e-learning-role-final/public/admin/chapitre/ajouter/<?= $c['id'] ?>">Ajouter un chapitre</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Gestion des étudiants -->
        <div class="admin-section">
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
                                <a href="/e-learning-role-final/public/admin/etudiant/supprimer/<?= $etudiant['id'] ?>" onclick="return confirm('Supprimer cet étudiant ?')">Supprimer</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</div>