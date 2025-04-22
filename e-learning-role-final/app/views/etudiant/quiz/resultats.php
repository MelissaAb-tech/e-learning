<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-dashboard.css">
<style>
    .results-container {
        max-width: 800px;
        margin: 0 auto;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 30px;
    }
    
    .results-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .results-header h2 {
        margin-bottom: 5px;
        color: #2c3e50;
    }
    
    .results-score {
        text-align: center;
        padding: 20px;
        margin: 20px 0;
        border-radius: 8px;
    }
    
    .results-score.pass {
        background-color: #d4edda;
        color: #155724;
    }
    
    .results-score.fail {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .results-score h3 {
        margin-top: 0;
    }
    
    .results-question {
        margin-bottom: 30px;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    .results-question h3 {
        margin-top: 0;
        margin-bottom: 15px;
        color: #2c3e50;
    }
    
    .results-options {
        margin-left: 15px;
    }
    
    .results-option {
        margin-bottom: 10px;
        padding: 12px 15px;
        border-radius: 4px;
        position: relative;
    }
    
    .results-option.correct {
        background-color: #d4edda;
        border-left: 4px solid #28a745;
    }
    
    .results-option.incorrect {
        background-color: #f8d7da;
        border-left: 4px solid #dc3545;
    }
    
    .results-option.neutral {
        background-color: #f2f2f2;
        border-left: 4px solid #6c757d;
    }
    
    .results-option-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: bold;
    }
    
    .results-actions {
        margin-top: 30px;
        text-align: center;
    }
    
    .results-button {
        display: inline-block;
        padding: 12px 24px;
        background-color: #3B82F6;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        margin: 0 10px;
    }
    
    .results-button:hover {
        background-color: #2563EB;
    }
</style>

<div class="admin-header">
    <h1>Résultats du quiz : <?= htmlspecialchars($quiz['titre']) ?></h1>
    <a href="/e-learning-role-final/public/cours/voir/<?= $cours['id'] ?>" style="color: white; margin-top: 10px; display: inline-block;">Retour au cours</a>
</div>

<div class="results-container">
    <div class="results-header">
        <h2>Quiz : <?= htmlspecialchars($quiz['titre']) ?></h2>
        <p><?= nl2br(htmlspecialchars($quiz['description'])) ?></p>
    </div>
    
    <?php 
    $scorePercent = ($tentative['score'] / $tentative['score_max']) * 100;
    $isPassed = $scorePercent >= 70; // 70% pour réussir
    $isPerfect = $scorePercent == 100; // 100% pour un score parfait
    ?>
    
    <div class="results-score <?= $isPassed ? 'pass' : 'fail' ?>">
        <h3>Votre score : <?= $tentative['score'] ?>/<?= $tentative['score_max'] ?> (<?= round($scorePercent) ?>%)</h3>
        <p>
            <?php if ($isPerfect): ?>
                <strong>Félicitations !</strong> Vous avez obtenu un score parfait !
            <?php elseif ($isPassed): ?>
                <strong>Bravo !</strong> Vous avez réussi ce quiz, mais vous pouvez encore vous améliorer.
            <?php else: ?>
                <strong>Vous n'avez pas obtenu un score parfait.</strong> Relisez le cours et retentez !
            <?php endif; ?>
        </p>
    </div>
    
    <h3>Détail des réponses :</h3>
    
    <?php foreach ($questions as $index => $question): ?>
        <div class="results-question">
            <h3>Question <?= $index + 1 ?> : <?= htmlspecialchars($question['texte']) ?></h3>
            <p class="question-type">
                Question à choix <?= $question['type'] === 'unique' ? 'unique' : 'multiples' ?>
            </p>
            
            <div class="results-options">
                <?php 
                // Les réponses sélectionnées par l'étudiant
                $selectedOptions = $question['reponses_etudiant'];
                
                // Afficher toutes les options
                foreach ($question['options'] as $option): 
                    // Déterminer la classe CSS pour cette option
                    $optionClass = 'neutral';
                    $iconHtml = '';
                    
                    // Si l'option a été sélectionnée par l'étudiant
                    if (in_array($option['id'], $selectedOptions)) {
                        if ($option['est_correcte']) {
                            $optionClass = 'correct';
                            $iconHtml = '<span class="results-option-icon">✓</span>';
                        } else {
                            $optionClass = 'incorrect';
                            $iconHtml = '<span class="results-option-icon">✗</span>';
                        }
                    }
                ?>
                    <div class="results-option <?= $optionClass ?>">
                        <?= htmlspecialchars($option['texte']) ?>
                        <?= $iconHtml ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    
    <div class="results-actions">
        <a href="/e-learning-role-final/public/quiz/etudiant/tenter/<?= $quiz['id'] ?>" class="results-button">Retenter le quiz</a>
        <a href="/e-learning-role-final/public/cours/voir/<?= $cours['id'] ?>" class="results-button">Retour au cours</a>
    </div>
</div>