<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-dashboard.css">

<div class="admin-header">
    <h1>Quiz du cours: <?= htmlspecialchars($cours['nom']) ?></h1>
    <a href="/e-learning-role-final/public/cours/voir/<?= $cours['id'] ?>" style="color: white; margin-top: 10px; display: inline-block;">Retour au cours</a>
</div>

<div class="admin-section">
    <div class="admin-section-header">
        <h2>Quiz disponibles</h2>
    </div>

    <?php if (empty($quizzes)): ?>
        <p>Aucun quiz n'est disponible pour ce cours.</p>
    <?php else: ?>
        <div class="card-grid">
            <?php foreach ($quizzes as $quiz): ?>
                <div class="admin-card">
                    <div class="admin-card-content">
                        <h3><?= htmlspecialchars($quiz['titre']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($quiz['description'])) ?></p>
                        
                        <?php if (isset($quiz['meilleure_tentative'])): ?>
                            <div style="margin: 15px 0; padding: 10px; border-radius: 5px; <?= ($quiz['meilleure_tentative']['score'] / $quiz['meilleure_tentative']['score_max'] >= 0.7) ? 'background-color: #d4edda; color: #155724;' : 'background-color: #f8d7da; color: #721c24;' ?>">
                                <strong>Votre score:</strong> <?= $quiz['meilleure_tentative']['score'] ?>/<?= $quiz['meilleure_tentative']['score_max'] ?> 
                                (<?= round(($quiz['meilleure_tentative']['score'] / $quiz['meilleure_tentative']['score_max']) * 100) ?>%)
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-actions">
                            <a href="/e-learning-role-final/public/quiz/etudiant/tenter/<?= $quiz['id'] ?>" style="display: inline-block; padding: 8px 16px; background-color: #3B82F6; color: white; border-radius: 4px; text-decoration: none;">
                                <?= isset($quiz['meilleure_tentative']) ? 'Refaire le quiz' : 'Commencer le quiz' ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>