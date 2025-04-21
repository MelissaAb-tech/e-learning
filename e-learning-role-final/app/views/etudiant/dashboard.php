<link rel="stylesheet" href="/e-learning-role-final/public/style/etudiant-dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="student-header">
    <div class="header-top">
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