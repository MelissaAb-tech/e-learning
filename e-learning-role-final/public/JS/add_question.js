// Initialiser le compteur d'options en fonction du nombre d'options
let optionCount = document.querySelectorAll('.option-row').length || 2;

function addOption() {
    const container = document.getElementById('options-container');
    const questionType = document.getElementById('type').value;
    const currentIndex = optionCount; // Capture l'index actuel

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

    deleteBtn.onclick = function () {
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

// Mettre à jour les numéros des options
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
    // Compter combien d'options sont affichées
    const options = document.querySelectorAll('.option-row');
    const count = options.length;

    // Si nous avons plus de 2 options, activer les boutons de suppression
    const deleteButtons = document.querySelectorAll('.delete-option-btn');
    deleteButtons.forEach(btn => {
        if (count > 2) {
            btn.style.visibility = 'visible';
            btn.disabled = false;
        } //Les désactiver
        else {
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
            // Une réponse doit être correcte
            if (input.value === '0' && document.getElementById('option0')) {
                document.getElementById('option0').required = true;
            }
        } else {
            input.type = 'checkbox';
            input.className = 'correct-checkbox';
            input.required = false; // Supprimer l'attribut required
        }
    });
}

// verfier que type correct est appliqué
window.onload = function () {
    updateQuestionType(document.getElementById('type').value);
    updateDeleteButtons();

    // Corriger les boutons de suppression existants
    document.querySelectorAll('.delete-option-btn').forEach((btn) => {
        // Remplacer l'ancien gestionnaire d'événements par un nouveau
        btn.onclick = function () {
            // Obtenir l'index à partir de l'ID du parent
            const row = this.closest('.option-row');
            const rowId = row.id;
            const rowIndex = rowId.replace('option-row-', '');
            deleteOption(rowIndex);
        };
    });
};