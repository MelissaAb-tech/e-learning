<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-dashboard.css">
<link rel="stylesheet" href="/e-learning-role-final/public/style/quiz-resultats.css">

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