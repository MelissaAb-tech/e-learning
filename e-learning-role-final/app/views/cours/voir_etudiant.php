<link rel="stylesheet" href="/e-learning-role-final/public/style/cours-card.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/e-learning-role-final/public/style/cours-etudiant.css">

<!-- Barre de navigation du cours -->
<div class="course-navbar">
    <div class="course-navbar-title">
        <h3> <?= htmlspecialchars($cours['nom']) ?> </h3>
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
        <a href="#" class="navbar-btn navbar-btn-danger" onclick="openLogoutModal(); return false;">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>


    </div>
</div>
<div style="margin:0px 100px">
    <?php
    // Récupérer le feedback de l'étudiant pour ce cours (s'il existe)
    $feedbackModel = $this->model('CoursFeedback');
    $feedback_existant = $feedbackModel->getByEtudiantAndCours($_SESSION['user']['id'], $cours['id']);
    $a_deja_donne_feedback = !empty($feedback_existant);
    ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success-message">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success_message'] ?>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error_message'] ?>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

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
            if (
                isset($quiz['meilleure_tentative']) &&
                $quiz['meilleure_tentative'] !== null &&
                isset($quiz['meilleure_tentative']['score']) &&
                isset($quiz['meilleure_tentative']['score_max']) &&
                $quiz['meilleure_tentative']['score'] == $quiz['meilleure_tentative']['score_max']
            ) {
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
                <div style="display: flex; gap: 15px; justify-content: center;">
                    <a href="/e-learning-role-final/public/certificat/generer/<?= $cours['id'] ?>" class="certificat-btn">
                        🏆 Obtenir mon certificat
                    </a>
                    <?php if (!$a_deja_donne_feedback): ?>
                        <button onclick="openFeedbackModal()" class="feedback-btn">
                            <i class="fas fa-star"></i> Donner mon avis sur ce cours
                        </button>
                    <?php else: ?>
                        <button onclick="openFeedbackModal()" class="feedback-btn feedback-btn-edit">
                            <i class="fas fa-pen"></i> Modifier mon avis
                        </button>
                    <?php endif; ?>
                </div>
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
                <div
                    style="height: 100%; background: linear-gradient(90deg, #4CAF50, #81C784); width: <?= $progression ?>%;">
                </div>
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
                    <div
                        style="height: 100%; background: linear-gradient(90deg, #FF9800, #FFB74D); width: <?= $quiz_progression ?>%;">
                    </div>
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
                    <button class="mark-btn" type="submit">
                        <?= in_array($chap['id'], $chapitres_vus ?? []) ? '❌ Marquer comme non terminé' : '✅ Marquer comme terminé' ?>
                    </button>
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
                        <?php if (
                            isset($quiz['meilleure_tentative']['score']) &&
                            isset($quiz['meilleure_tentative']['score_max']) &&
                            $quiz['meilleure_tentative']['score_max'] > 0
                        ): ?>
                            <div
                                style="background-color: <?= $scoreParfait ? '#e8f5e9' : '#fff3e0' ?> !important; padding: 8px !important; border-radius: 4px !important; margin-bottom: 15px !important;">
                                <strong>Score précédent:</strong>
                                <?= $quiz['meilleure_tentative']['score'] ?>/<?= $quiz['meilleure_tentative']['score_max'] ?>
                                (<?= round(($quiz['meilleure_tentative']['score'] / $quiz['meilleure_tentative']['score_max']) * 100) ?>%)
                                <?php if ($scoreParfait): ?>
                                    <span style="color: #2e7d32; margin-left: 8px; font-weight: bold;">✅ Parfait !</span>
                                <?php endif; ?>
                            </div>
                        <?php elseif (isset($quiz['meilleure_tentative']['score']) && isset($quiz['meilleure_tentative']['score_max'])): ?>
                            <div
                                style="background-color: #fff3e0 !important; padding: 8px !important; border-radius: 4px !important; margin-bottom: 15px !important;">
                                <strong>Score précédent:</strong>
                                <?= $quiz['meilleure_tentative']['score'] ?? 0 ?>/<?= $quiz['meilleure_tentative']['score_max'] ?? 0 ?>
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
            <button class="modal-btn modal-btn-cancel" onclick="closeResetModal()">
                <i class="fas fa-times"></i> Annuler
            </button>
            <a href="/e-learning-role-final/public/cours/reinitialiser/<?= $cours['id'] ?>"
                class="modal-btn modal-btn-danger">
                <i class="fas fa-sync-alt"></i> Réinitialiser
            </a>
        </div>
    </div>
</div>
<!-- Modal de confirmation pour la déconnexion -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <div class="modal-title">Déconnexion</div>
        <div class="modal-text">
            Êtes-vous sûr de vouloir vous déconnecter ?<br>
            Vous serez redirigé vers la page d'accueil.
        </div>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="closeLogoutModal()">
                <i class="fas fa-times"></i> Annuler
            </button>
            <a href="/e-learning-role-final/public/logout" class="modal-btn modal-btn-danger">
                <i class="fas fa-sign-out-alt"></i> Se déconnecter
            </a>
        </div>
    </div>
</div>

<!-- Modal de feedback pour le cours -->
<div id="feedbackModal" class="modal">
    <div class="modal-content feedback-modal-content">
        <div class="modal-title">
            <?= $a_deja_donne_feedback ? 'Modifier votre avis' : 'Partagez votre avis sur ce cours' ?>
        </div>
        <form method="POST" action="/e-learning-role-final/public/cours/feedback/<?= $cours['id'] ?>">
            <div class="form-group">
                <label>Note globale :</label>
                <div class="star-rating">
                    <input type="radio" name="rating" id="rating-5" value="5" <?= ($a_deja_donne_feedback && $feedback_existant['note'] == 5) ? 'checked' : '' ?>><label for="rating-5">&#9733;</label>
                    <input type="radio" name="rating" id="rating-4" value="4" <?= ($a_deja_donne_feedback && $feedback_existant['note'] == 4) ? 'checked' : '' ?>><label for="rating-4">&#9733;</label>
                    <input type="radio" name="rating" id="rating-3" value="3" <?= ($a_deja_donne_feedback && $feedback_existant['note'] == 3) ? 'checked' : '' ?>><label for="rating-3">&#9733;</label>
                    <input type="radio" name="rating" id="rating-2" value="2" <?= ($a_deja_donne_feedback && $feedback_existant['note'] == 2) ? 'checked' : '' ?>><label for="rating-2">&#9733;</label>
                    <input type="radio" name="rating" id="rating-1" value="1" <?= ($a_deja_donne_feedback && $feedback_existant['note'] == 1) ? 'checked' : '' ?>><label for="rating-1">&#9733;</label>
                </div>
            </div>

            <div class="form-group">
                <label for="commentaire">Votre avis sur ce cours :</label>
                <textarea name="commentaire" id="commentaire" placeholder="Partagez votre expérience avec ce cours..."
                    required><?= $a_deja_donne_feedback ? htmlspecialchars($feedback_existant['commentaire']) : '' ?></textarea>
            </div>

            <div class="modal-buttons">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="closeFeedbackModal()">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="submit" class="modal-btn modal-btn-confirm">
                    <?php if ($a_deja_donne_feedback): ?>
                        <i class="fas fa-pen"></i> Mettre à jour mon avis
                    <?php else: ?>
                        <i class="fas fa-paper-plane"></i> Envoyer mon avis
                    <?php endif; ?>
                </button>
            </div>
        </form>
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

    // Fonctions pour gérer le modal de feedback
    function openFeedbackModal() {
        document.getElementById('feedbackModal').style.display = 'flex';
    }

    function closeFeedbackModal() {
        document.getElementById('feedbackModal').style.display = 'none';
    }

    // Fermer les modals si l'utilisateur clique en dehors
    window.onclick = function (event) {
        const resetModal = document.getElementById('resetModal');
        const feedbackModal = document.getElementById('feedbackModal');

        if (event.target === resetModal) {
            closeResetModal();
        } else if (event.target === feedbackModal) {
            closeFeedbackModal();
        }
    }

    function openLogoutModal() {
        document.getElementById('logoutModal').style.display = 'flex';
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').style.display = 'none';
    }
    window.onclick = function (event) {
        const resetModal = document.getElementById('resetModal');
        const feedbackModal = document.getElementById('feedbackModal');
        const logoutModal = document.getElementById('logoutModal');

        if (event.target === resetModal) {
            closeResetModal();
        } else if (event.target === feedbackModal) {
            closeFeedbackModal();
        } else if (event.target === logoutModal) {
            closeLogoutModal();
        }
    }
</script>