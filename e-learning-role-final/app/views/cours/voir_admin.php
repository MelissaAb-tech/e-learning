<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-form.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/e-learning-role-final/public/style/cours-admin.css">

<!-- Barre de navigation du cours -->
<div class="course-navbar">
    <div class="course-navbar-title">
        <h3> <?= htmlspecialchars($cours['nom']) ?></h3>
    </div>

    <div class="course-navbar-buttons">
        <a href="/e-learning-role-final/public/admin/dashboard" class="navbar-btn navbar-btn-primary">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="/e-learning-role-final/public/forum/cours/<?= $cours['id'] ?>" class="navbar-btn navbar-btn-primary">
            <i class="fas fa-comments"></i> Forum
        </a>
        <a href="#" class="navbar-btn navbar-btn-danger" onclick="openLogoutModal(); return false;">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>

    </div>
</div>

<div style="margin:0px 100px">
    <div style="display: flex; gap: 40px; align-items: flex-start; justify-content: space-between; margin:0px">

        <!-- infos du cours -->
        <div style="flex: 2;">
            <h2><?= $cours['nom'] ?></h2>
            <p><strong>Professeur :</strong> <?= $cours['professeur'] ?></p>
            <p><strong>Niveau :</strong> <?= $cours['niveau'] ?> • <strong>Durée :</strong> <?= $cours['duree'] ?></p>
            <p><?= nl2br($cours['contenu']) ?></p>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <a href="/e-learning-role-final/public/quiz/index/<?= $cours['id'] ?>" style="background-color: #3B82F6; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none;">
                    Gérer les quiz
                </a>
                <a href="/e-learning-role-final/public/admin/chapitre/ajouter/<?= $cours['id'] ?>" style="background-color: #4CAF50; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none;">
                    Ajouter un chapitre
                </a>
            </div>
        </div>

        <div style="width: 20%; border: 1px solid #ddd; padding: 20px; border-radius: 10px; background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3 style="margin-bottom: 15px; font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 5px;">Statistiques du cours</h3>

            <?php
            // Récupérer tous les chapitres du cours
            $chapitreModel = $this->model('Chapitre');
            $chapitres = $chapitreModel->getByCoursId($cours['id']);
            $chapitres_total = count($chapitres);

            // Récupérer tous les quiz du cours
            $quizModel = $this->model('Quiz');
            $quizzes = $quizModel->getByCoursId($cours['id']);
            $has_quizzes = !empty($quizzes);
            $quiz_total = count($quizzes);

            // Récupérer tous les étudiants inscrits au cours
            $inscriptionModel = $this->model('CoursInscription');
            $etudiantsInscrits = $inscriptionModel->getEtudiantsParCours($cours['id']);

            $etudiants_termines = 0;
            $etudiants_en_cours = 0;
            $total_progression = 0;

            $tentativeModel = $this->model('QuizTentative');

            // Pour chaque étudiant inscrit calculer sa progression
            foreach ($etudiantsInscrits as $etudiant) {
                $user_id = $etudiant['id'];
                $a_commence = false;

                // Vérification des chapitres
                $chapitres_vus = $chapitreModel->getVusParUser($user_id);
                $chapitres_termine = 0;

                foreach ($chapitres as $chap) {
                    if (in_array($chap['id'], $chapitres_vus)) {
                        $chapitres_termine++;
                        $a_commence = true;
                    }
                }

                // Calcul de la progression des chapitres
                $chapitre_progress = $chapitres_total > 0 ? ($chapitres_termine / $chapitres_total) * 100 : 100;

                // Vérification des quiz
                $quiz_parfait = 0;

                if ($has_quizzes) {
                    foreach ($quizzes as $quiz) {
                        $meilleureTentative = $tentativeModel->getMeilleureTentative($user_id, $quiz['id']);
                        if (
                            $meilleureTentative &&
                            isset($meilleureTentative['score']) &&
                            isset($meilleureTentative['score_max']) &&
                            $meilleureTentative['score_max'] > 0 &&
                            $meilleureTentative['score'] == $meilleureTentative['score_max']
                        ) {
                            $quiz_parfait++;
                            $a_commence = true;
                        } else if ($meilleureTentative) {
                            $a_commence = true;
                        }
                    }
                }

                // Calcul de la progression des quiz
                $quiz_progress = $quiz_total > 0 ? ($quiz_parfait / $quiz_total) * 100 : 100;

                // Calcul de la progression globale 
                $global_progress = $has_quizzes ?
                    ($chapitre_progress + $quiz_progress) / 2 :
                    $chapitre_progress;

                // Ajouter à la progression totale
                $total_progression += $global_progress;

                // Déterminer si l'étudiant a terminé ou est en cours
                if ($chapitre_progress == 100 && (!$has_quizzes || $quiz_progress == 100)) {
                    $etudiants_termines++;
                } else {
                    // il est "en cours"
                    $etudiants_en_cours++;
                }
            }

            // Nombre total d'étudiants inscrits
            $nombre_inscrits = count($etudiantsInscrits);

            // Calcul de la moyenne de progression 
            $moyenne = $nombre_inscrits > 0 ? round($total_progression / $nombre_inscrits) : 0;

            // Récupérer la note moyenne et le nombre d'avis pour ce cours
            $feedbackModel = $this->model('CoursFeedback');
            $noteMoyenne = $feedbackModel->getMoyenneNotesParCours($cours['id']);
            $nombreAvis = $feedbackModel->getNombreFeedbacksParCours($cours['id']);
            ?>

            <div style="margin-bottom: 10px;">
                <span style="font-weight: bold;">Note moyenne :</span>
                <?php if ($nombreAvis > 0): ?>
                    <?= number_format($noteMoyenne, 1) ?>/5 (<?= $nombreAvis ?> avis)
                <?php else: ?>
                    Aucun avis pour le moment
                <?php endif; ?>
            </div>

            <!-- Statistiques existantes -->
            <div style="margin-bottom: 10px;">
                <span style="font-weight: bold;">Étudiants inscrits :</span> <?= $nombre_inscrits ?>
            </div>

            <div style="margin-bottom: 10px;">
                <span style="font-weight: bold;">Moyenne de progression :</span> <?= $moyenne ?> %
            </div>

            <div style="margin-bottom: 10px;">
                <span style="font-weight: bold;">Étudiants ayant terminé :</span> <?= $etudiants_termines ?>
            </div>

            <div style="margin-bottom: 10px;">
                <span style="font-weight: bold;">Étudiants en cours :</span> <?= $etudiants_en_cours ?>
            </div>

            <!-- voir les avis -->
            <?php if ($nombreAvis > 0): ?>
                <a href="/e-learning-role-final/public/admin/cours/feedbacks/<?= $cours['id'] ?>"
                    style="display: inline-block; background-color: #3B82F6; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; margin-top: 15px; text-align: center; width: 85%; font-weight: 500;">
                    <i class="fas fa-comments"></i> Voir les avis des étudiants
                </a>
            <?php endif; ?>
        </div>
    </div>

    <h3>Chapitres du cours :</h3>
</div>
<?php foreach ($chapitres as $chap): ?>
    <div style="margin: 10px 100px; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
        <h4><?= htmlspecialchars($chap['titre']) ?></h4>
        <p><?= nl2br(htmlspecialchars($chap['description'])) ?></p>

        <!-- Affichage des fichiers PDF -->
        <?php if (!empty($chap['pdfs'])): ?>
            <div class="admin-files-section">
                <h5>Documents PDF</h5>
                <div class="admin-file-list">
                    <?php foreach ($chap['pdfs'] as $pdf): ?>
                        <div class="admin-file-item">
                            <i class="fas fa-file-pdf"></i>
                            <a href="/e-learning-role-final/public/pdfs/<?= $pdf['pdf'] ?>" target="_blank">
                                <?= htmlspecialchars($pdf['pdf']) ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Affichage des vidéos -->
        <?php if (!empty($chap['videos'])): ?>
            <div class="admin-files-section">
                <h5>Vidéos</h5>
                <?php foreach ($chap['videos'] as $video): ?>
                    <?php if ($video['est_youtube'] == 1): ?>
                        <div class="admin-video-item">
                            <div class="admin-video-header">
                                <i class="fab fa-youtube"></i>
                                <span>Vidéo YouTube: <?= htmlspecialchars($video['video']) ?></span>
                            </div>
                            <?php
                            $video_id = "";
                            if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $video['video'], $matches)) {
                                $video_id = $matches[1];
                            } elseif (preg_match('/youtu\.be\/([^?]+)/', $video['video'], $matches)) {
                                $video_id = $matches[1];
                            }
                            ?>
                            <?php if (!empty($video_id)): ?>
                                <div class="admin-video-container">
                                    <iframe width="100%" height="360" src="https://www.youtube.com/embed/<?= $video_id ?>"
                                        frameborder="0" allowfullscreen></iframe>
                                </div>
                            <?php else: ?>
                                <a href="<?= $video['video'] ?>" target="_blank">Voir la vidéo YouTube</a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="admin-video-item">
                            <div class="admin-video-header">
                                <i class="fas fa-file-video"></i>
                                <span>Fichier vidéo MP4: <?= htmlspecialchars($video['video']) ?></span>
                            </div>
                            <div class="admin-video-container">
                                <video controls width="100%" style="max-width: 500px;">
                                    <source src="/e-learning-role-final/public/videos/<?= $video['video'] ?>" type="video/mp4">
                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                </video>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p class="admin-chapter-actions">
            <a href="/e-learning-role-final/public/admin/chapitre/modifier/<?= $chap['id'] ?>/<?= $cours['id'] ?>">✏️ Modifier le chapitre</a> |
            <a href="/e-learning-role-final/public/admin/chapitre/supprimer/<?= $chap['id'] ?>/<?= $cours['id'] ?>" onclick="return confirm('Supprimer ce chapitre ?')">🗑️ Supprimer le chapitre</a>
        </p>
    </div>
<?php endforeach; ?>

<!--confirmation pour la déconnexion admin -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <div class="modal-title">Déconnexion</div>
        <div class="modal-text">
            Êtes-vous sûr de vouloir vous déconnecter ?
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
<script>
    function openLogoutModal() {
        document.getElementById('logoutModal').style.display = 'flex';
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const logoutModal = document.getElementById('logoutModal');
        if (event.target === logoutModal) {
            closeLogoutModal();
        }
    }
</script>