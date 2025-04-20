<link rel="stylesheet" href="/e-learning-role-final/public/style/cours-card.css">
<style>
    .course-box {
        position: relative;
    }

    .course-link-overlay {
        position: absolute;
        inset: 0;
        z-index: 1;
    }

    .course-info {
        position: relative;
        z-index: 2;
    }
</style>

<h2>Dashboard Admin</h2>
<a href="/e-learning-role-final/public/admin/ajouter">➕ Ajouter un cours</a>

<div class="course-container">
    <?php foreach ($cours as $c): ?>
        <div class="course-box">
            <a href="/e-learning-role-final/public/cours/voir/<?= $c['id'] ?>" class="course-link-overlay"></a>

            <img src="/e-learning-role-final/public/images/<?= $c['image'] ?>" class="course-img" alt="Image du cours">
            <div class="course-info">
                <span class="course-badge"><?= $c['niveau'] ?> • <?= $c['duree'] ?></span>
                <h3><?= $c['nom'] ?></h3>
                <p class="prof"><?= $c['professeur'] ?></p>
                <p><?= nl2br($c['contenu']) ?></p>

                <a href="/e-learning-role-final/public/admin/modifier/<?= $c['id'] ?>">Modifier</a> |
                <a href="/e-learning-role-final/public/admin/supprimer/<?= $c['id'] ?>" onclick="return confirm('Supprimer ce cours ?')">🗑️ Supprimer</a> |
                <a href="/e-learning-role-final/public/admin/chapitre/ajouter/<?= $c['id'] ?>">➕ Ajouter un chapitre</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>