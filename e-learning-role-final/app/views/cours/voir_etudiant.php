<link rel="stylesheet" href="/e-learning-role-final/public/style/cours-card.css">
<div class="course-box" style="margin:30px;">
    <img src="/e-learning-role-final/public/images/<?= $cours['image'] ?>" class="course-img" alt="Cours">
    <div class="course-info">
        <span class="course-badge"><?= $cours['niveau'] ?> • <?= $cours['duree'] ?></span>
        <h2><?= $cours['nom'] ?></h2>
        <p class="prof">Professeur : <?= $cours['professeur'] ?></p>
        <p><?= nl2br($cours['contenu']) ?></p>
    </div>
</div>

<style>
    .accordion-item {
        border: 1px solid #ccc;
        border-radius: 6px;
        margin: 10px 30px;
        overflow: hidden;
    }

    .accordion-title {
        background-color: #f2f2f2;
        padding: 12px;
        font-weight: bold;
        cursor: pointer;
    }

    .accordion-content {
        display: none;
        padding: 15px;
        background-color: #fff;
    }
</style>

<h3 style="margin-left: 30px;">Chapitres :</h3>

<?php foreach ($chapitres as $index => $chap): ?>
    <div class="accordion-item">
        <div class="accordion-title" onclick="toggleChapitre(<?= $index ?>)">
            <?= htmlspecialchars($chap['titre']) ?>
        </div>
        <div class="accordion-content" id="chapitre-content-<?= $index ?>">
            <p><?= nl2br(htmlspecialchars($chap['description'])) ?></p>

            <?php if (!empty($chap['pdf'])): ?>
                <p>📄 <a href="/e-learning-role-final/public/pdfs/<?= $chap['pdf'] ?>" target="_blank">Télécharger le PDF</a></p>
            <?php endif; ?>

            <?php if (!empty($chap['video'])): ?>
                <?php if (str_contains($chap['video'], 'youtube.com')): ?>
                    <p>🎥 <a href="<?= $chap['video'] ?>" target="_blank">Voir la vidéo YouTube</a></p>
                <?php else: ?>
                    <div style="max-width: 640px; margin: 10px auto;">
                        <video controls width="100%" style="border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                            <source src="/e-learning-role-final/public/videos/<?= $chap['video'] ?>" type="video/mp4">
                        </video>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

<script>
    function toggleChapitre(index) {
        const content = document.getElementById("chapitre-content-" + index);
        content.style.display = content.style.display === "block" ? "none" : "block";
    }
</script>