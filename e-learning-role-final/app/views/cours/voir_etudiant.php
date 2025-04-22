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
    
    /* Styles renforcés pour les quiz */
    .quiz-card-fixed {
        background-color: #f9f9f9 !important;
        border: 2px solid #ddd !important;
        border-radius: 8px !important;
        padding: 15px !important;
        margin: 15px 30px !important;
        position: relative !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1) !important;
        min-height: 120px !important;
    }
    
    .quiz-title-fixed {
        margin-top: 0 !important;
        font-size: 18px !important;
        color: #333 !important;
        font-weight: bold !important;
    }
    
    .quiz-description-fixed {
        color: #666 !important;
        margin-bottom: 15px !important;
    }
    
    .btn-quiz-fixed {
        display: inline-block !important;
        background-color: #ff5722 !important; /* Orange vif pour être bien visible */
        color: white !important;
        font-size: 16px !important;
        font-weight: bold !important;
        text-align: center !important;
        padding: 10px 20px !important;
        border-radius: 5px !important;
        text-decoration: none !important;
        margin-top: 10px !important;
        border: none !important;
        box-shadow: 0 3px 6px rgba(0,0,0,0.2) !important;
        cursor: pointer !important;
        position: relative !important;
        z-index: 10 !important;
    }
    
    .btn-quiz-fixed:hover {
        background-color: #e64a19 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3) !important;
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
    </div>
<?php else: ?>
    <div style="max-width: 400px; margin: 20px auto; margin-top: 15px; padding: 15px; background-color: #fff3cd; border-left: 6px solid #ffc107; border-radius: 6px; text-align: center;">
        <strong>Vous n'êtes pas loin !</strong> Terminez tous les chapitres <br>
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

<!-- Section des quiz avec style renforcé -->
<h3 style="margin-left: 30px;">Quiz du cours :</h3>

<div>
    <?php if (empty($quizzes)): ?>
        <p style="margin-left: 30px;">Aucun quiz n'est disponible pour ce cours pour le moment.</p>
    <?php else: ?>
        <?php foreach ($quizzes as $quiz): ?>
            <div class="quiz-card-fixed">
                <!-- Titre et description -->
                <h4 class="quiz-title-fixed"><?= htmlspecialchars($quiz['titre']) ?></h4>
                <p class="quiz-description-fixed"><?= nl2br(htmlspecialchars($quiz['description'])) ?></p>
                
                <!-- Score précédent si disponible avec vérifications supplémentaires -->
                <?php if (isset($quiz['meilleure_tentative']) && $quiz['meilleure_tentative'] !== null): ?>
                    <?php if (isset($quiz['meilleure_tentative']['score']) && 
                              isset($quiz['meilleure_tentative']['score_max']) && 
                              $quiz['meilleure_tentative']['score_max'] > 0): ?>
                        <div style="background-color: #e8f5e9 !important; padding: 8px !important; border-radius: 4px !important; margin-bottom: 15px !important;">
                            <strong>Score précédent:</strong> <?= $quiz['meilleure_tentative']['score'] ?>/<?= $quiz['meilleure_tentative']['score_max'] ?> 
                            (<?= round(($quiz['meilleure_tentative']['score'] / $quiz['meilleure_tentative']['score_max']) * 100) ?>%)
                        </div>
                    <?php elseif (isset($quiz['meilleure_tentative']['score']) && isset($quiz['meilleure_tentative']['score_max'])): ?>
                        <div style="background-color: #fff3e0 !important; padding: 8px !important; border-radius: 4px !important; margin-bottom: 15px !important;">
                            <strong>Score précédent:</strong> <?= $quiz['meilleure_tentative']['score'] ?? 0 ?>/<?= $quiz['meilleure_tentative']['score_max'] ?? 0 ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Bouton "Refaire le quiz" quand il y a eu une tentative -->
                    <a href="/e-learning-role-final/public/quiz/etudiant/tenter/<?= $quiz['id'] ?>" class="btn-quiz-fixed">
                        🔄 Refaire le quiz
                    </a>
                <?php else: ?>
                    <!-- Bouton "Commencer le quiz" quand il n'y a jamais eu de tentative -->
                    <a href="/e-learning-role-final/public/quiz/etudiant/tenter/<?= $quiz['id'] ?>" class="btn-quiz-fixed">
                        ▶️ Commencer le quiz
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    function toggleChapitre(index) {
        const content = document.getElementById("chapitre-content-" + index);
        content.style.display = content.style.display === "block" ? "none" : "block";
    }
</script>