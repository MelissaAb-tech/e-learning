<link rel="stylesheet" href="/e-learning-role-final/public/style/cours-card.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
    
    .progress-container {
        max-width: 800px;
        margin: 30px auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .progress-header {
        text-align: center;
        margin-bottom: 20px;
        font-size: 18px;
        font-weight: bold;
        color: #2c3e50;
    }
    
    .progress-section {
        margin-bottom: 15px;
    }
    
    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
        font-weight: bold;
    }
    
    .certificat-btn {
        display: inline-block;
        background-color: #4CAF50;
        color: white;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        padding: 12px 24px;
        border-radius: 6px;
        text-decoration: none;
        margin-top: 10px;
        border: none;
        box-shadow: 0 3px 6px rgba(0,0,0,0.2);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .certificat-btn:hover {
        background-color: #45a049;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
    
    /* Styles pour la barre de navigation */
    .course-navbar {
        background: linear-gradient(90deg, #2c3e50, #3B82F6);
        color: white;
        padding: 12px 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .course-navbar-title {
        font-size: 18px;
        font-weight: bold;
        color: white;
        margin-right: 20px;
    }
    
    .course-navbar-buttons {
        display: flex;
        gap: 10px;
    }
    
    .navbar-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .navbar-btn-primary {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
    }
    
    .navbar-btn-primary:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }
    
    .navbar-btn-warning {
        background-color: #F97316;
        color: white;
    }
    
    .navbar-btn-warning:hover {
        background-color: #EA580C;
    }
    
    /* Style pour le modal de confirmation */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }
    
    .modal-content {
        background-color: white;
        padding: 25px;
        border-radius: 8px;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .modal-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
    }
    
    .modal-text {
        margin-bottom: 20px;
        color: #555;
        line-height: 1.5;
    }
    
    .modal-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    
    .modal-btn {
        padding: 8px 16px;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        border: none;
    }
    
    .modal-btn-cancel {
        background-color: #e2e8f0;
        color: #4a5568;
    }
    
    .modal-btn-confirm {
        background-color: #ef4444;
        color: white;
    }
    
    @media (max-width: 768px) {
        .course-navbar {
            flex-direction: column;
            padding: 10px 20px;
        }
        
        .course-navbar-title {
            margin-bottom: 10px;
            margin-right: 0;
        }
        
        .course-navbar-buttons {
            width: 100%;
            justify-content: center;
        }
    }
    
    .documents-section, .videos-section {
        margin: 20px 0;
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
    }
    
    .documents-section h4, .videos-section h4 {
        margin-top: 0;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 8px;
        color: #2c3e50;
    }
    
    .document-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .document-item {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
    }
    
    .document-item i {
        color: #e53e3e;
        margin-right: 10px;
        font-size: 18px;
    }
    
    .document-item a {
        color: #3182ce;
        text-decoration: none;
    }
    
    .document-item a:hover {
        text-decoration: underline;
    }
    
    .video-item {
        margin-bottom: 25px;
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        overflow: hidden;
    }
    
    .video-item h5 {
        background-color: #f1f5f9;
        margin: 0;
        padding: 10px 15px;
        border-bottom: 1px solid #e5e7eb;
        color: #4b5563;
    }
    
    .video-container {
        max-height: 500px;
        max-width: 700;
        padding: 15px;
    }
    
    iframe, video {
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        max-width: 100%;
    }
</style>

<!-- Barre de navigation du cours -->
<div class="course-navbar">
    <div class="course-navbar-title">
        <?= htmlspecialchars($cours['nom']) ?>
    </div>
    
    <div class="course-navbar-buttons">
        <a href="/e-learning-role-final/public/etudiant/dashboard" class="navbar-btn navbar-btn-primary">
            <i class="fas fa-home"></i> Mes cours
        </a>
        <a href="/e-learning-role-final/public/forum/cours/<?= $cours['id'] ?>" class="navbar-btn navbar-btn-primary">
            <i class="fas fa-comments"></i> Forum
        </a>
        <a href="#" class="navbar-btn navbar-btn-warning" onclick="openResetModal()">
            <i class="fas fa-sync-alt"></i> Réinitialiser
        </a>
    </div>
</div>

<div class="course-box" style="margin:30px;">
    <img src="/e-learning-role-final/public/images/<?= $cours['image'] ?>" class="course-img" alt="Cours">
    <div class="course-info">
        <span class="course-badge"><?= $cours['niveau'] ?> • <?= $cours['duree'] ?></span>
        <h2><?= $cours['nom'] ?></h2>
        <p class="prof">Professeur : <?= $cours['professeur'] ?></p>
        <p><?= nl2br($cours['contenu']) ?></p>
    </div>
</div>

<?php 
// Calculer la progression des chapitres
$chapitres_total = count($chapitres);
$chapitres_termine = 0;
foreach ($chapitres as $chap) {
    if (in_array($chap['id'], $chapitres_vus ?? [])) {
        $chapitres_termine++;
    }
}
$progression = $chapitres_total > 0 ? round(($chapitres_termine / $chapitres_total) * 100) : 0;

// Calculer la progression des quiz
$quiz_total = count($quizzes);
$quiz_parfait = 0;

if ($quiz_total > 0) {
    foreach ($quizzes as $quiz) {
        if (isset($quiz['meilleure_tentative']) && 
            $quiz['meilleure_tentative'] !== null && 
            isset($quiz['meilleure_tentative']['score']) && 
            isset($quiz['meilleure_tentative']['score_max']) && 
            $quiz['meilleure_tentative']['score'] == $quiz['meilleure_tentative']['score_max']) {
            $quiz_parfait++;
        }
    }
    
    $quiz_progression = $quiz_total > 0 ? round(($quiz_parfait / $quiz_total) * 100) : 0;
} else {
    $quiz_progression = 0;
}

// Déterminer si toutes les sections sont complétées à 100%
$chapitres_complets = ($chapitres_total > 0) ? ($progression == 100) : true;
$quiz_complets = ($quiz_total > 0) ? ($quiz_progression == 100) : true;
$cours_complet = $chapitres_complets && $quiz_complets;
?>

<!-- Section de progression globale placée avant les chapitres -->
<div class="progress-container">
    <div class="progress-header">
        <?php if ($cours_complet): ?>
            <div style="color: #2e7d32; margin-bottom: 15px;">
                <span style="font-size: 24px;">🎓</span> Félicitations ! Vous maîtrisez parfaitement ce cours.
            </div>
            <a href="/e-learning-role-final/public/certificat/generer/<?= $cours['id'] ?>" class="certificat-btn">
                🏆 Obtenir mon certificat
            </a>
        <?php else: ?>
            Obtenez 100% à toutes les sections du cours pour obtenir votre certificat !
        <?php endif; ?>
    </div>
    
    <!-- Barre de progression des chapitres -->
    <div class="progress-section">
        <div class="progress-label">
            <span>Progression des chapitres :</span>
            <span><?= $chapitres_termine ?>/<?= $chapitres_total ?> chapitres (<?= $progression ?>%)</span>
        </div>
        <div style="background: #e0e0e0; border-radius: 20px; height: 12px; overflow: hidden;">
            <div style="height: 100%; background: linear-gradient(90deg, #4CAF50, #81C784); width: <?= $progression ?>%;"></div>
        </div>
    </div>
    
    <!-- Barre de progression des quiz -->
    <?php if ($quiz_total > 0): ?>
    <div class="progress-section">
        <div class="progress-label">
            <span>Progression des quiz :</span>
            <span><?= $quiz_parfait ?>/<?= $quiz_total ?> quiz parfaits (<?= $quiz_progression ?>%)</span>
        </div>
        <div style="background: #e0e0e0; border-radius: 20px; height: 12px; overflow: hidden;">
            <div style="height: 100%; background: linear-gradient(90deg, #FF9800, #FFB74D); width: <?= $quiz_progression ?>%;"></div>
        </div>
    </div>
    <?php endif; ?>
</div>

<h3 style="margin-left: 30px;">Chapitres :</h3>

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

            <!-- Affichage des fichiers PDF -->
            <?php if (!empty($chap['pdfs'])): ?>
                <div class="documents-section">
                    <h4>Documents PDF</h4>
                    <div class="document-list">
                        <?php foreach ($chap['pdfs'] as $pdf): ?>
                            <div class="document-item">
                                <i class="fas fa-file-pdf"></i>
                                <a href="/e-learning-role-final/public/pdfs/<?= $pdf['pdf'] ?>" target="_blank">
                                    <?= htmlspecialchars($pdf['pdf']) ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Affichage des vidéos (YouTube et MP4) -->
            <?php if (!empty($chap['videos'])): ?>
                <div class="videos-section">
                    <h4>Vidéos</h4>
                    <?php foreach ($chap['videos'] as $video): ?>
                        <?php if ($video['est_youtube'] == 1): ?>
                            <div class="video-item youtube-video">
                                <h5>Vidéo YouTube</h5>
                                <?php
                                // Extraire l'ID de la vidéo YouTube
                                $video_id = "";
                                if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $video['video'], $matches)) {
                                    $video_id = $matches[1];
                                } elseif (preg_match('/youtu\.be\/([^?]+)/', $video['video'], $matches)) {
                                    $video_id = $matches[1];
                                }
                                ?>
                                <?php if (!empty($video_id)): ?>
                                    <div class="video-container">
                                        <iframe width="100%" height="360" src="https://www.youtube.com/embed/<?= $video_id ?>" 
                                            frameborder="0" allowfullscreen></iframe>
                                    </div>
                                <?php else: ?>
                                    <a href="<?= $video['video'] ?>" target="_blank">Voir la vidéo YouTube</a>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="video-item mp4-video">
                                <h5>Fichier vidéo MP4</h5>
                                <div class="video-container">
                                    <video controls width="100%">
                                        <source src="/e-learning-role-final/public/videos/<?= $video['video'] ?>" type="video/mp4">
                                        Votre navigateur ne supporte pas la lecture de vidéos.
                                    </video>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
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
                    <?php 
                    $scoreParfait = (isset($quiz['meilleure_tentative']['score']) && 
                                    isset($quiz['meilleure_tentative']['score_max']) && 
                                    $quiz['meilleure_tentative']['score'] == $quiz['meilleure_tentative']['score_max']);
                    ?>
                    <?php if (isset($quiz['meilleure_tentative']['score']) && 
                              isset($quiz['meilleure_tentative']['score_max']) && 
                              $quiz['meilleure_tentative']['score_max'] > 0): ?>
                        <div style="background-color: <?= $scoreParfait ? '#e8f5e9' : '#fff3e0' ?> !important; padding: 8px !important; border-radius: 4px !important; margin-bottom: 15px !important;">
                            <strong>Score précédent:</strong> <?= $quiz['meilleure_tentative']['score'] ?>/<?= $quiz['meilleure_tentative']['score_max'] ?> 
                            (<?= round(($quiz['meilleure_tentative']['score'] / $quiz['meilleure_tentative']['score_max']) * 100) ?>%)
                            <?php if ($scoreParfait): ?>
                                <span style="color: #2e7d32; margin-left: 8px; font-weight: bold;">✅ Parfait !</span>
                            <?php endif; ?>
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

<!-- Modal de confirmation pour la réinitialisation -->
<div id="resetModal" class="modal">
    <div class="modal-content">
        <div class="modal-title">Réinitialiser le cours</div>
        <div class="modal-text">
            Êtes-vous sûr de vouloir réinitialiser votre progression sur ce cours ? 
            <br><br>
            <strong>Cette action supprimera :</strong>
            <ul style="margin-top: 5px;">
                <li>Votre progression dans les chapitres</li>
                <li>Vos tentatives de quiz</li>
                <li>Vos scores obtenus</li>
            </ul>
            <br>
            Cette action est irréversible.
        </div>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="closeResetModal()">Annuler</button>
            <a href="/e-learning-role-final/public/cours/reinitialiser/<?= $cours['id'] ?>" class="modal-btn modal-btn-confirm">Réinitialiser</a>
        </div>
    </div>
</div>

<script>
    function toggleChapitre(index) {
        const content = document.getElementById("chapitre-content-" + index);
        content.style.display = content.style.display === "block" ? "none" : "block";
    }
    
    // Fonctions pour gérer le modal de réinitialisation
    function openResetModal() {
        document.getElementById('resetModal').style.display = 'flex';
    }
    
    function closeResetModal() {
        document.getElementById('resetModal').style.display = 'none';
    }
    
    // Fermer le modal si l'utilisateur clique en dehors
    window.onclick = function(event) {
        const modal = document.getElementById('resetModal');
        if (event.target === modal) {
            closeResetModal();
        }
    }
</script>