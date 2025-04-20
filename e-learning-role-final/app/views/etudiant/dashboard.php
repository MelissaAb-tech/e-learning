<link rel="stylesheet" href="/e-learning-role-final/public/style/cours-card.css">
<h2>Dashboard Etudiant</h2>
<div class="course-container">
    <?php foreach ($cours as $c): ?>
        <a href="/e-learning-role-final/public/cours/voir/<?= $c['id'] ?>" style="text-decoration: none; color: inherit;">
            <div class="course-box">
                <img src="/e-learning-role-final/public/images/<?= $c['image'] ?>" class="course-img" alt="Image du cours">
                <div class="course-info">
                    <span class="course-badge"><?= $c['niveau'] ?> • <?= $c['duree'] ?></span>
                    <h3><?= $c['nom'] ?></h3>
                    <p class="prof"><?= $c['professeur'] ?></p>
                    <p><?= nl2br($c['contenu']) ?></p>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</div>