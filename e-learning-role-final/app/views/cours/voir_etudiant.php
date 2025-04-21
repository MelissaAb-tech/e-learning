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
<!-- barre progression -->
<!-- barre de progression du cours -->
<div style="max-width: 400px; margin: 20px auto; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
    <p style="margin-bottom: 10px; font-weight: bold; text-align: center;">
        Progression du cours : <?= $chapitres_termine ?>/<?= $chapitres_total ?> chapitres (<?= $progression ?>%)
    </p>
    <div style="background: #e0e0e0; border-radius: 20px; height: 12px; overflow: hidden;">
        <div style="height: 100%; background: linear-gradient(90deg, #4CAF50, #81C784); width: <?= $progression ?>%;"></div>
    </div>
</div>

<?php if ($progression == 100): ?>
    <div style="max-width: 400px; margin: 20px auto; margin-top: 15px; padding: 15px; background-color: #e8f5e9; border-left: 6px solid #4CAF50; border-radius: 6px; text-align: center;">
        <strong>Bravo !</strong> Vous avez complété tous les chapitres.<br>
        <a href="/e-learning-role-final/public/quiz/etudiant/tenter/<?= $cours['id'] ?>" style="display: inline-block; margin-top: 10px; padding: 8px 16px; background-color: #4CAF50; color: white; border-radius: 4px; text-decoration: none;">
            Passer les quiz du cours
        </a>
    </div>
<?php else: ?>
    <div style="max-width: 400px; margin: 20px auto; margin-top: 15px; padding: 15px; background-color: #fff3cd; border-left: 6px solid #ffc107; border-radius: 6px; text-align: center;">
        <strong>Vous êtes pas loin !</strong> Terminez tous les chapitres <br>
        Progression actuelle : <?= $progression ?>%
    </div>
<?php endif; ?>




<?php foreach ($chapitres as $chap): ?>
    <div class="accordion-item">
        <div class="accordion-title" onclick="toggleChapitre(<?= $chap['id'] ?>)">
            <?= htmlspecialchars($chap['titre']) ?>
            <?php if (in_array($chap['id'], $chapitres_vus ?? [])): ?>
                ✅
            <?php endif; ?>
        </div>

        <div class="accordion-content" id="chapitre-content-<?= $chap['id'] ?>">
            <p><?= nl2br(htmlspecialchars($chap['description'])) ?></p>

            <?php if (!empty($chap['pdf'])): ?>
                <p>📄 <a href="/e-learning-role-final/public/pdfs/<?= $chap['pdf'] ?>" target="_blank">Télécharger le PDF</a></p>
            <?php endif; ?>

            <?php if (!empty($chap['video'])): ?>
                <div style="max-width: 640px; margin: 10px auto;">
                    <video controls width="100%">
                        <source src="/e-learning-role-final/public/videos/<?= $chap['video'] ?>" type="video/mp4">
                    </video>
                </div>
            <?php endif; ?>

            <form method="POST" action="/e-learning-role-final/public/chapitre/valider">
                <input type="hidden" name="chapitre_id" value="<?= $chap['id'] ?>">
                <button type="submit">✅ Marquer comme terminé</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>


<script>
    function toggleChapitre(index) {
        const content = document.getElementById("chapitre-content-" + index);
        content.style.display = content.style.display === "block" ? "none" : "block";
    }
</script>