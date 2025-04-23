<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


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
                                <a href="/e-learning-role-final/public/admin/etudiant/supprimer/<?= $etudiant['id'] ?>" onclick="return confirm('Supprimer cet étudiant ?')">Supprimer</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</div>