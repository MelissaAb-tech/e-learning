<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-form.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
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
</style>

<!-- Barre de navigation du cours -->
<div class="course-navbar">
    <div class="course-navbar-title">
        <?= htmlspecialchars($cours['nom']) ?>
    </div>
    
    <div class="course-navbar-buttons">
        <a href="/e-learning-role-final/public/admin/dashboard" class="navbar-btn navbar-btn-primary">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="/e-learning-role-final/public/forum/cours/<?= $cours['id'] ?>" class="navbar-btn navbar-btn-primary">
            <i class="fas fa-comments"></i> Forum
        </a>
    </div>
</div>

<div style="display: flex; gap: 40px; align-items: flex-start; justify-content: space-between;">

    <!-- infos du cours -->
    <div style="flex: 2;">
        <h2><?= $cours['nom'] ?></h2>
        <p><strong>Professeur :</strong> <?= $cours['professeur'] ?></p>
        <p><strong>Niveau :</strong> <?= $cours['niveau'] ?> • <strong>Durée :</strong> <?= $cours['duree'] ?></p>
        <p><?= nl2br($cours['contenu']) ?></p>
        
        <!-- Boutons d'action -->
        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <a href="/e-learning-role-final/public/quiz/index/<?= $cours['id'] ?>" style="background-color: #3B82F6; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none;">
                Gérer les quiz
            </a>
            <a href="/e-learning-role-final/public/admin/chapitre/ajouter/<?= $cours['id'] ?>" style="background-color: #4CAF50; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none;">
                Ajouter un chapitre
            </a>
        </div>
    </div>

    <!-- infos des statistique -->
    <div style="flex: 1; border: 1px solid #ddd; padding: 20px; border-radius: 10px; background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h3 style="margin-bottom: 15px; font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 5px;">Statistiques du cours</h3>

        <?php
        $total = 0;
        $fini = 0;
        $etudiants_termines = [];
        $etudiants_en_cours = [];

        foreach ($stats as $s) {
            $total += $s['progression'];
            if ($s['termine']) {
                $fini++;
                $etudiants_termines[] = $s;
            } else {
                $etudiants_en_cours[] = $s;
            }
        }

        $moyenne = count($stats) > 0 ? round($total / count($stats)) : 0;
        ?>

        <div style="margin-bottom: 10px;">
            <span style="font-weight: bold;">Étudiants inscrits :</span> <?= count($stats) ?>
        </div>

        <div style="margin-bottom: 10px;">
            <span style="font-weight: bold;">Moyenne de progression :</span> <?= $moyenne ?> %
        </div>

        <div style="margin-bottom: 10px;">
            <span style="font-weight: bold;">Étudiants ayant terminé :</span> <?= $fini ?>
        </div>

        <div style="margin-bottom: 20px;">
            <span style="font-weight: bold;">Étudiants en cours :</span> <?= count($etudiants_en_cours) ?>
        </div>

        <div style="border-top: 1px solid #eee; padding-top: 10px;">
            <span style="font-weight: bold; display: block; margin-bottom: 8px;">Progression individuelle :</span>
            <ul style="padding-left: 0; list-style: none; max-height: 180px; overflow-y: auto; font-size: 14px;">
                <?php foreach ($stats as $s): ?>
                    <li style="padding: 4px 0; border-bottom: 1px solid #f3f3f3;">
                        <span style="display: inline-block; width: 55%;"><?= $s['nom'] ?></span>
                        <span style="display: inline-block; width: 40%; text-align: right; color: <?= $s['progression'] === 100 ? '#2e7d32' : '#999'; ?>;">
                            <?= $s['progression'] ?>%
                            <?= $s['progression'] === 100 ? '(terminé)' : '' ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<h3>Chapitres du cours :</h3>

<?php foreach ($chapitres as $chap): ?>
    <div style="margin: 10px 0; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
        <h4><?= htmlspecialchars($chap['titre']) ?></h4>
        <p><?= nl2br(htmlspecialchars($chap['description'])) ?></p>

        <?php if (!empty($chap['pdf'])): ?>
            <p>📄 <a href="/e-learning-role-final/public/pdfs/<?= $chap['pdf'] ?>" target="_blank">Voir le PDF</a></p>
        <?php endif; ?>

        <?php if (!empty($chap['video'])): ?>
            <?php if (str_contains($chap['video'], 'youtube.com')): ?>
                <p>🎥 <a href="<?= $chap['video'] ?>" target="_blank">Voir la vidéo YouTube</a></p>
            <?php else: ?>
                <video controls width="100%" style="max-width: 500px;">
                    <source src="/e-learning-role-final/public/videos/<?= $chap['video'] ?>" type="video/mp4">
                </video>
            <?php endif; ?>
        <?php endif; ?>

        <p>
            <a href="/e-learning-role-final/public/admin/chapitre/supprimer/<?= $chap['id'] ?>/<?= $cours['id'] ?>" onclick="return confirm('Supprimer ce chapitre ?')">🗑️ Supprimer le chapitre</a>
        </p>
    </div>
<?php endforeach; ?>

</div>