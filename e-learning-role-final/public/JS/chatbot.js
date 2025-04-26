document.addEventListener('DOMContentLoaded', function () {
    let chatbotQA = [];
    let defaultResponses = {};

    // Récupérer automatiquement le role
    const chatbotWrapper = document.getElementById('chatbot-wrapper');
    const role = chatbotWrapper.getAttribute('data-role') || 'etudiant'; // valeur par défaut

    // Choisir quel fichier JSON charger selon le role
    let jsonFile = role === 'admin'
        ? '/e-learning-role-final/public/JS/chatbot-data-admin.json'
        : '/e-learning-role-final/public/JS/chatbot-data-etudiant.json';

    fetch(jsonFile)
        .then(response => response.json())
        .then(data => {
            chatbotQA = data.chatbotQA;
            defaultResponses = data.defaultResponses;
            init();
        })
        .catch(error => console.error('Erreur de chargement des données du chatbot:', error));

    // Récupération des éléments DOM
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotContainer = document.getElementById('chatbot-container');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const chatbotForm = document.getElementById('chatbot-form');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotMinimize = document.getElementById('chatbot-minimize');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotSuggestions = document.getElementById('chatbot-suggestions');

    // Initialisation du chatbot
    function init() {
        setTimeout(() => {
            addMessage(getRandomResponse(defaultResponses.greetings), 'bot');
            showSuggestions(defaultResponses.suggestions);
        }, 500);

        chatbotToggle.addEventListener('click', toggleChatbot);
        chatbotMinimize.addEventListener('click', minimizeChatbot);
        chatbotClose.addEventListener('click', closeChatbot);
        chatbotForm.addEventListener('submit', handleSubmit);
    }

    // Affiche ou cache le chatbot
    function toggleChatbot() {
        chatbotContainer.classList.toggle('chatbot-collapsed');
        chatbotContainer.classList.toggle('chatbot-expanded');
        if (chatbotContainer.classList.contains('chatbot-expanded')) {
            chatbotInput.focus();
        }
    }

    // Réduit le chatbot
    function minimizeChatbot() {
        chatbotContainer.classList.remove('chatbot-expanded');
        chatbotContainer.classList.add('chatbot-collapsed');
    }

    // Ferme le chatbot
    function closeChatbot() {
        chatbotContainer.classList.remove('chatbot-expanded');
        chatbotContainer.classList.add('chatbot-collapsed');
    }

    // Gere l'envoi du formulaire
    function handleSubmit(e) {
        e.preventDefault();
        const message = chatbotInput.value.trim();
        if (message) {
            addMessage(message, 'user');
            chatbotInput.value = '';
            processMessage(message);
        }
    }

    // Ajoute un message 
    function addMessage(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}-message`;
        messageDiv.textContent = message;
        chatbotMessages.appendChild(messageDiv);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    // Animation de écriture
    function showTyping() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'typing-indicator';
        for (let i = 0; i < 3; i++) {
            const dot = document.createElement('div');
            dot.className = 'typing-dot';
            typingDiv.appendChild(dot);
        }
        chatbotMessages.appendChild(typingDiv);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        return typingDiv;
    }

    // Traitement du message 
    function processMessage(message) {
        const typing = showTyping();

        setTimeout(() => {
            typing.remove();
            const response = findAnswer(message);
            addMessage(response, 'bot');

            const suggestions = findRelatedQuestions(message);
            showSuggestions(suggestions);
        }, 1000);
    }

    // Trouve la réponse la plus proche
    function findAnswer(message) {
        const userMessage = message.toLowerCase();
        let bestMatch = null;
        let bestScore = 0;

        chatbotQA.forEach(qa => {
            const score = calculateSimilarity(userMessage, qa.question.toLowerCase());
            if (score > bestScore) {
                bestScore = score;
                bestMatch = qa;
            }
        });

        return bestMatch && bestScore > 0.3
            ? bestMatch.answer
            : "Désolé, je ne comprends pas ta question. Peux-tu la reformuler ?";
    }

    // Calcule une similarité entre deux textes
    function calculateSimilarity(str1, str2) {
        const words1 = str1.split(/\s+/);
        const words2 = str2.split(/\s+/);
        let matches = 0;

        words1.forEach(word => {
            if (words2.includes(word)) matches++;
        });

        return matches / Math.max(words1.length, words2.length);
    }

    // Trouve des questions liées à suggérer
    function findRelatedQuestions(message) {
        const userMessage = message.toLowerCase();
        return chatbotQA
            .filter(qa => calculateSimilarity(userMessage, qa.question.toLowerCase()) > 0.2)
            .slice(0, 3)
            .map(qa => qa.question);
    }

    // Affiche des suggestions
    function showSuggestions(suggestions) {
        chatbotSuggestions.innerHTML = '';
        suggestions.forEach(suggestion => {
            const button = document.createElement('button');
            button.className = 'suggestion-button';
            button.textContent = suggestion;
            button.addEventListener('click', () => {
                addMessage(suggestion, 'user');
                processMessage(suggestion);
            });
            chatbotSuggestions.appendChild(button);
        });
    }

    // Sélectionne un message aléatoire
    function getRandomResponse(responses) {
        return responses[Math.floor(Math.random() * responses.length)];
    }
});
