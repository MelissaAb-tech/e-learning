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
        
        <div style="margin-top: 20px;">
            <button type="submit">Ajouter la question</button>
            <a href="/e-learning-role-final/public/quiz/questions/<?= $quiz['id'] ?>" style="display: inline-block; margin-top: 10px;">Retour aux questions</a>
        </div>
    </form>
</div>

<style>
    .option-row {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    .option-row input[type="text"] {
        flex: 1;
        margin-bottom: 0;
    }
    .option-correct {
        display: flex;
        align-items: center;
        width: 40px;
        justify-content: center;
    }
    .option-correct input {
        margin: 0;
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    #add-option {
        background-color: #f0f0f0;
        color: #333;
        padding: 5px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 10px;
    }
    .label-hint {
        font-size: 13px;
        color: #666;
        font-weight: normal;
        font-style: italic;
    }
    /* Style spécifique pour le bouton de suppression avec !important pour éviter les conflits */
    .delete-option-btn {
        background-color: #f44336 !important;
        color: white !important;
        border: none !important;
        border-radius: 50% !important;
        width: 25px !important;
        height: 25px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 18px !important;
        margin-left: 10px !important;
        cursor: pointer !important;
        padding: 0 !important;
        line-height: 1 !important;
    }
    .delete-option-btn:hover {
        background-color: #d32f2f !important;
    }
</style>

<script>
    let optionCount = 2;
    
    function addOption() {
        const container = document.getElementById('options-container');
        const questionType = document.getElementById('type').value;
        
        const div = document.createElement('div');
        div.className = 'option-row';
        div.id = 'option-row-' + optionCount;
        
        const checkDiv = document.createElement('div');
        checkDiv.className = 'option-correct';
        
        const checkbox = document.createElement('input');
        checkbox.type = questionType === 'unique' ? 'radio' : 'checkbox';
        checkbox.name = 'correctes[]';
        checkbox.value = optionCount;
        checkbox.className = questionType === 'unique' ? 'correct-radio' : 'correct-checkbox';
        
        const input = document.createElement('input');
        input.type = 'text';
        input.name = `options[${optionCount}]`;
        input.placeholder = `Option ${optionCount + 1}`;
        input.required = true;
        
        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'delete-option-btn';
        deleteBtn.innerHTML = '×';
        deleteBtn.style.backgroundColor = '#f44336';
        deleteBtn.style.color = 'white';
        deleteBtn.onclick = function() { deleteOption(optionCount); };
        
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
        }
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
                if (input.value === '0') {
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
    };
</script>