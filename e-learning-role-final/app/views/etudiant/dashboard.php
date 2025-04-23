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
    
    /* Style pour le message de succès */
    .feedback-success {
        background-color: #D1FAE5;
        color: #065F46;
        padding: 15px 20px;
        border-radius: 8px;
        margin: 20px 60px 0;
        text-align: center;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        animation: fadeIn 0.5s ease-in, fadeOut 0.5s 5s ease-in forwards;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
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
            <textarea name="commentaire" id="commentaire" rows="4" placeholder="Exprimez-vous librement..." required></textarea>
        </div>

        <button type="submit" class="btn">Envoyer mon avis</button>
    </form>
</div>