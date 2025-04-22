<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-dashboard.css">
<style>
    .quiz-container {
        max-width: 800px;
        margin: 0 auto;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 30px;
    }
    
    .quiz-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .quiz-header h2 {
        margin-bottom: 5px;
        color: #2c3e50;
    }
    
    .quiz-header p {
        color: #7f8c8d;
    }
    
    .quiz-question {
        margin-bottom: 30px;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #3B82F6;
    }
    
    .quiz-question h3 {
        margin-top: 0;
        margin-bottom: 15px;
        color: #2c3e50;
    }
    
    .quiz-options {
        margin-left: 15px;
    }
    
    /* Styles améliorés pour les options de quiz */
    .quiz-option {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        background-color: white;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        padding: 0;
        transition: all 0.2s ease;
    }
    
    .quiz-option:hover {
        border-color: #bdbdbd;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .quiz-option input[type="radio"],
    .quiz-option input[type="checkbox"] {
        margin: 0;
        width: 24px;
        height: 24px;
        cursor: pointer;
        margin: 10px;
        accent-color: #3B82F6;
    }
    
    .quiz-option label {
        flex: 1;
        padding: 10px;
        border-radius: 0 4px 4px 0;
        cursor: pointer;
        font-size: 16px;
    }
    
    .question-type {
        font-size: 13px;
        color: #7f8c8d;
        font-style: italic;
        margin-bottom: 15px;
    }
    
    .quiz-actions {
        margin-top: 30px;
        text-align: center;
    }
    
    .quiz-submit {
        padding: 12px 24px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .quiz-submit:hover {
        background-color: #3e8e41;
    }
    
    .quiz-cancel {
        display: inline-block;
        margin-left: 10px;
        padding: 12px 24px;
        background-color: #e74c3c;
        color: white;
        text-decoration: none;
        border-radius: 6px;
    }
    
    .quiz-cancel:hover {
        background-color: #c0392b;
    }
</style>

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