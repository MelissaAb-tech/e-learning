<link rel="stylesheet" href="/e-learning-role-final/public/style/etudiant-dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
        <a href="/e-learning-role-final/public/cours/voir/<?= $c['id'] ?>" class="course-card">
            <img src="/e-learning-role-final/public/images/<?= $c['image'] ?>" alt="Image du cours">
            <div class="course-details">
                <span class="course-badge"><?= $c['niveau'] ?> • <?= $c['duree'] ?></span>
                <h3><?= $c['nom'] ?></h3>
                <p class="prof">Par <?= $c['professeur'] ?></p>
                <p class="desc"><?= nl2br($c['contenu']) ?></p>
                <div class="go-link"> Accéder au cours</div>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<div class="section-divider">
    <h2>Exprimez-vous</h2>
    <p>Vos retours sont précieux pour améliorer la plateforme</p>
</div>

<!-- Feedback  -->
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