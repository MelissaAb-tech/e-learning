<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-form.css">

<div class="admin-form-container">
    <h2>Ajouter une question</h2>
    <p>Pour le quiz: <strong><?= htmlspecialchars($quiz['titre']) ?></strong></p>

    <?php if (isset($error)): ?>
        <div style="color: red; margin-bottom: 15px;"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="texte">Texte de la question</label>
        <textarea name="texte" id="texte" rows="3" required></textarea>

        <label for="type">Type de question</label>
        <select name="type" id="type" required onchange="updateQuestionType(this.value)">
            <option value="unique">Choix unique (boutons radio)</option>
            <option value="multiple">Choix multiple (cases à cocher)</option>
        </select>

        <div id="options-container">
            <label>Options de réponse <span class="label-hint">(cochez les réponses correctes)</span></label>
            <div class="option-row" id="option-row-0">
                <div class="option-correct">
                    <input type="radio" name="correctes[]" value="0" class="correct-radio" id="option0">
                </div>
                <input type="text" name="options[0]" placeholder="Option 1" required>
                <button type="button" class="delete-option-btn" onclick="deleteOption(0)" style="visibility: hidden;" disabled>×</button>
            </div>
            <div class="option-row" id="option-row-1">
                <div class="option-correct">
                    <input type="radio" name="correctes[]" value="1" class="correct-radio">
                </div>
                <input type="text" name="options[1]" placeholder="Option 2" required>
                <button type="button" class="delete-option-btn" onclick="deleteOption(1)" style="visibility: hidden;" disabled>×</button>
            </div>
        </div>

        <button type="button" id="add-option" onclick="addOption()">+ Ajouter une option</button>
        <hr style="margin:15px 0px">
        <div style="margin-top: 20px;justify-content:space-between;display:flex;">
            <button class="submitBtn" type="submit">Ajouter la question</button>
            <a class="back-btn" href="/e-learning-role-final/public/quiz/questions/<?= $quiz['id'] ?>">Retour aux questions</a>
        </div>
    </form>
</div>

<link rel="stylesheet" href="/e-learning-role-final/public/style/add-question.css">

<script src="/e-learning-role-final/public/JS/add_question.js"></script>