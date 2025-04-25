<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-dashboard.css">
<link rel="stylesheet" href="/e-learning-role-final/public/style/quiz-tenter.css">

<div class="admin-header">
    <h1><?= htmlspecialchars($quiz['titre']) ?></h1>
    <a href="/e-learning-role-final/public/cours/voir/<?= $cours['id'] ?>" style="color: white; margin-top: 10px; display: inline-block;">Retour au cours</a>
</div>

<div class="quiz-container">
    <div class="quiz-header">
        <h2>Quiz : <?= htmlspecialchars($quiz['titre']) ?></h2>
        <p><?= nl2br(htmlspecialchars($quiz['description'])) ?></p>
    </div>
    
    <?php if (empty($questions)): ?>
        <p>Ce quiz ne contient aucune question.</p>
    <?php else: ?>
        <form method="POST" action="/e-learning-role-final/public/quiz/etudiant/soumettre/<?= $quiz['id'] ?>">
            <?php foreach ($questions as $index => $question): ?>
                <div class="quiz-question">
                    <h3>Question <?= $index + 1 ?> : <?= htmlspecialchars($question['texte']) ?></h3>
                    <div class="question-type">
                        <?= $question['type'] === 'unique' ? 'Choisissez une seule réponse' : 'Choisissez toutes les réponses correctes' ?>
                    </div>
                    
                    <div class="quiz-options">
                        <?php foreach ($question['options'] as $option): ?>
                            <div class="quiz-option">
                                <input 
                                    type="<?= $question['type'] === 'unique' ? 'radio' : 'checkbox' ?>" 
                                    name="reponses[<?= $question['id'] ?>]<?= $question['type'] === 'multiple' ? '[]' : '' ?>" 
                                    id="option-<?= $question['id'] ?>-<?= $option['id'] ?>" 
                                    value="<?= $option['id'] ?>"
                                >
                                <label for="option-<?= $question['id'] ?>-<?= $option['id'] ?>"><?= htmlspecialchars($option['texte']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="quiz-actions">
                <button type="submit" class="quiz-submit">Soumettre mes réponses</button>
                <a href="/e-learning-role-final/public/cours/voir/<?= $cours['id'] ?>" class="quiz-cancel">Annuler</a>
            </div>
        </form>
    <?php endif; ?>
</div>