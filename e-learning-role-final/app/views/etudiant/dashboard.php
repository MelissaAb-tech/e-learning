<link rel="stylesheet" href="/e-learning-role-final/public/style/etudiant-dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    /* Style pour les boutons d'action */
    .course-actions {
        margin-top: 15px;
        display: flex;
        gap: 10px;
    }
    
    .btn-access {
        display: inline-block;
        background-color: #3B82F6;
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.3s;
    }
    
    .btn-access:hover {
        background-color: #2563EB;
    }
    
    .btn-subscribe {
        display: inline-block;
        background-color: #10B981;
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.3s;
    }
    
    .btn-subscribe:hover {
        background-color: #059669;
    }
    
    .btn-unsubscribe {
        display: inline-block;
        background-color: #F87171;
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.3s;
    }
    
    .btn-unsubscribe:hover {
        background-color: #EF4444;
    }
</style>

<div class="student-header">
    <div class="header-top">
        <a href="/e-learning-role-final/public/logout" class="logout-button">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>
        <a href="/e-learning-role-final/public/etudiant/mon-compte" class="account-button">
            <i class="fas fa-user"></i> Mon compte
        </a>
    </div>
    <h1>Bienvenue sur votre espace étudiant</h1>
    <p>Accédez à vos cours disponibles et suivez votre progression</p>
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
                            <i class="fas fa-book-open"></i> Accéder au cours
                        </a>
                        <a href="/e-learning-role-final/public/etudiant/desinscrire/<?= $c['id'] ?>" class="btn-unsubscribe" onclick="return confirm('Êtes-vous sûr de vouloir vous désinscrire de ce cours ?')">
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