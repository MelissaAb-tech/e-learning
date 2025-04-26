<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-dashboard.css">

<div class="admin-header">
    <h1>Questions du quiz: <?= htmlspecialchars($quiz['titre']) ?></h1>
    <a class="back-btn" href="/e-learning-role-final/public/quiz/index/<?= $cours['id'] ?>" >Retour aux quiz</a>
</div>

<div class="admin-section">
    <div class="admin-section-header">
        <h2>Toutes les questions</h2>
        <a href="/e-learning-role-final/public/quiz/addQuestion/<?= $quiz['id'] ?>" class="btn">Ajouter une question</a>
    </div>

    <?php if (empty($questions)): ?>
        <p>Aucune question n'a été créée pour ce quiz.</p>
    <?php else: ?>
        <?php foreach ($questions as $index => $question): ?>
            <div class="admin-card" style="margin-bottom: 20px;">
                <div class="admin-card-content">
                    <h3>Question <?= $index + 1 ?>: <?= htmlspecialchars($question['texte']) ?></h3>
                    <p><strong>Type:</strong> <?= $question['type'] === 'unique' ? 'Choix unique (radio)' : 'Choix multiple (checkbox)' ?></p>
                    
                    <h4>Options:</h4>
                    <ul style="list-style-type: none; padding-left: 0;">
                        <?php foreach ($question['options'] as $option): ?>
                            <li style="margin-bottom: 5px; padding: 5px; background-color: <?= $option['est_correcte'] ? '#d4edda' : '#f8f9fa' ?>; border-radius: 4px;">
                                <?= htmlspecialchars($option['texte']) ?>
                                <?= $option['est_correcte'] ? ' ✓' : '' ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="card-actions">
                        <a href="/e-learning-role-final/public/quiz/editQuestion/<?= $question['id'] ?>">Modifier</a>
                        <a href="/e-learning-role-final/public/quiz/deleteQuestion/<?= $question['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?');">Supprimer</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>