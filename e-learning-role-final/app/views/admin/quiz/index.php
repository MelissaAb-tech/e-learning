<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-dashboard.css">

<div class="admin-header">
    <h1>Quiz pour le cours: <?= htmlspecialchars($cours['nom']) ?></h1>
    <a class="back-btn" href="/e-learning-role-final/public/cours/voir/<?= $cours['id'] ?>" >Retour au cours</a>
</div>

<div class="admin-section">
    <div class="admin-section-header">
        <h2>Tous les quiz</h2>
        <a href="/e-learning-role-final/public/quiz/create/<?= $cours['id'] ?>" class="btn">Créer un nouveau quiz</a>
    </div>

    <?php if (empty($quizzes)): ?>
        <p>Aucun quiz n'a été créé pour ce cours.</p>
    <?php else: ?>
        <div class="card-grid">
            <?php foreach ($quizzes as $quiz): ?>
                <div class="admin-card">
                    <div class="admin-card-content">
                        <h3><?= htmlspecialchars($quiz['titre']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($quiz['description'])) ?></p>
                        <div class="card-actions">
                            <a href="/e-learning-role-final/public/quiz/questions/<?= $quiz['id'] ?>">Gérer les questions</a>
                            <a href="/e-learning-role-final/public/quiz/edit/<?= $quiz['id'] ?>">Modifier</a>
                            <a href="/e-learning-role-final/public/quiz/delete/<?= $quiz['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce quiz ?');">Supprimer</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>