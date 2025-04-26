<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-form.css">

<div class="admin-form-container">
    <h2>Modifier la question</h2>
    <p>Pour le quiz: <strong><?= htmlspecialchars($quiz['titre']) ?></strong></p>

    <?php if (isset($error)): ?>
        <div style="color: red; margin-bottom: 15px;"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="texte">Texte de la question</label>
        <textarea name="texte" id="texte" rows="3" required><?= htmlspecialchars($question['texte']) ?></textarea>

        <label for="type">Type de question</label>
        <select name="type" id="type" required onchange="updateQuestionType(this.value)">
            <option value="unique" <?= $question['type'] === 'unique' ? 'selected' : '' ?>>Choix unique (boutons radio)</option>
            <option value="multiple" <?= $question['type'] === 'multiple' ? 'selected' : '' ?>>Choix multiple (cases à cocher)</option>
        </select>

        <div id="options-container">
            <label>Options de réponse <span class="label-hint">(cochez les réponses correctes)</span></label>
            <?php foreach ($options as $index => $option): ?>
                <div class="option-row" id="option-row-<?= $index ?>">
                    <div class="option-correct">
                        <input type="<?= $question['type'] === 'unique' ? 'radio' : 'checkbox' ?>"
                            name="correctes[]"
                            value="<?= $index ?>"
                            class="<?= $question['type'] === 'unique' ? 'correct-radio' : 'correct-checkbox' ?>"
                            <?= $option['est_correcte'] ? 'checked' : '' ?>
                            <?= ($question['type'] === 'unique' && $index === 0) ? 'id="option0"' : '' ?>>
                    </div>
                    <input type="text" name="options[<?= $index ?>]" value="<?= htmlspecialchars($option['texte']) ?>" placeholder="Option <?= $index + 1 ?>" required>
                    <input type="hidden" name="option_ids[<?= $index ?>]" value="<?= $option['id'] ?>">
                    <button type="button" class="delete-option-btn" onclick="deleteOption(<?= $index ?>)">×</button>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="button" id="add-option" onclick="addOption()">+ Ajouter une option</button>
        <hr style="margin:15px 0px">
        <div style="margin-top: 20px;justify-content:space-between;display:flex;">
            <button class="submitBtn" type="submit">Mettre à jour la question</button>
            <a class="back-btn" href="/e-learning-role-final/public/quiz/questions/<?= $quiz['id'] ?>">Retour aux questions</a>
        </div>
    </form>
</div>

<link rel="stylesheet" href="/e-learning-role-final/public/style/edit-question.css">

<script>
    // Initialiser le compteur d'options en fonction du nombre d'options existantes
    let optionCount = <?= count($options) ?>;

    function addOption() {
        const container = document.getElementById('options-container');
        const questionType = document.getElementById('type').value;
        const currentIndex = optionCount; // Capturer l'index actuel

        const div = document.createElement('div');
        div.className = 'option-row';
        div.id = 'option-row-' + currentIndex;

        const checkDiv = document.createElement('div');
        checkDiv.className = 'option-correct';

        const checkbox = document.createElement('input');
        checkbox.type = questionType === 'unique' ? 'radio' : 'checkbox';
        checkbox.name = 'correctes[]';
        checkbox.value = currentIndex;
        checkbox.className = questionType === 'unique' ? 'correct-radio' : 'correct-checkbox';

        const input = document.createElement('input');
        input.type = 'text';
        input.name = `options[${currentIndex}]`;
        input.placeholder = `Option ${currentIndex + 1}`;
        input.required = true;

        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'delete-option-btn';
        deleteBtn.innerHTML = '×';
        deleteBtn.style.backgroundColor = '#f44336';
        deleteBtn.style.color = 'white';

        // Utiliser une meilleure méthode pour associer l'événement de clic
        deleteBtn.onclick = function() {
            deleteOption(currentIndex);
        };

        checkDiv.appendChild(checkbox);
        div.appendChild(checkDiv);
        div.appendChild(input);
        div.appendChild(deleteBtn);

        container.appendChild(div);
        optionCount++;

        updateDeleteButtons();
    }

    function deleteOption(index) {
        const optionRow = document.getElementById('option-row-' + index);
        if (optionRow) {
            optionRow.remove();
            updateDeleteButtons();
            updateOptionNumbers(); // Mettre à jour les numéros d'options
        }
    }

    // Fonction pour mettre à jour les numéros des options
    function updateOptionNumbers() {
        const optionRows = document.querySelectorAll('.option-row');

        optionRows.forEach((row, idx) => {
            // Mettre à jour le placeholder du champ input
            const input = row.querySelector('input[type="text"]');
            if (input) {
                input.placeholder = `Option ${idx + 1}`;
            }
        });
    }

    function updateDeleteButtons() {
        // Compter combien d'options sont actuellement affichées
        const options = document.querySelectorAll('.option-row');
        const count = options.length;

        // Si nous avons plus de 2 options, activer les boutons de suppression, sinon les désactiver
        const deleteButtons = document.querySelectorAll('.delete-option-btn');
        deleteButtons.forEach(btn => {
            if (count > 2) {
                btn.style.visibility = 'visible';
                btn.disabled = false;
            } else {
                btn.style.visibility = 'hidden';
                btn.disabled = true;
            }
        });
    }

    function updateQuestionType(type) {
        const inputs = document.querySelectorAll('.correct-radio, .correct-checkbox');

        inputs.forEach(input => {
            if (type === 'unique') {
                input.type = 'radio';
                input.className = 'correct-radio';
                // Si c'est une question à choix unique, au moins une réponse doit être correcte
                if (input.value === '0' && document.getElementById('option0')) {
                    document.getElementById('option0').required = true;
                }
            } else {
                input.type = 'checkbox';
                input.className = 'correct-checkbox';
                input.required = false; // Supprimer l'attribut required pour les checkboxes
            }
        });
    }

    // S'assurer que le type correct est appliqué au chargement de la page
    window.onload = function() {
        updateQuestionType(document.getElementById('type').value);
        updateDeleteButtons();

        // Corriger les boutons de suppression existants
        document.querySelectorAll('.delete-option-btn').forEach((btn) => {
            // Remplacer l'ancien gestionnaire d'événements par un nouveau
            btn.onclick = function() {
                // Nous devons obtenir l'index à partir de l'ID du parent
                const row = this.closest('.option-row');
                const rowId = row.id;
                const rowIndex = rowId.replace('option-row-', '');
                deleteOption(rowIndex);
            };
        });
    };
</script>