<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-form.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="admin-form-container">
    <h2>Ajouter un chapitre</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-section">
            <label for="titre">Titre du chapitre</label>
            <input type="text" name="titre" id="titre" required>
        </div>

        <div class="form-section">
            <label for="description">Description</label>
            <textarea name="description" id="description" required></textarea>
        </div>

        <!-- Section pour les fichiers PDF -->
        <div class="multi-files-section">
            <div class="section-title">
                <h3><i class="fas fa-file-pdf"></i> Fichiers PDF</h3>
            </div>
            
            <div id="pdf-container">
                <!-- Les éléments seront ajoutés ici dynamiquement -->
            </div>
            
            <button type="button" class="add-file-btn" onclick="addPdfField()">
                <i class="fas fa-plus-circle"></i> Ajouter un PDF
            </button>
        </div>

        <!-- Section pour les liens YouTube -->
        <div class="multi-files-section">
            <div class="section-title">
                <h3><i class="fab fa-youtube"></i> Liens YouTube</h3>
            </div>
            
            <div id="youtube-container">
                <!-- Les éléments seront ajoutés ici dynamiquement -->
            </div>
            
            <button type="button" class="add-file-btn yt-btn" onclick="addYoutubeField()">
                <i class="fas fa-plus-circle"></i> Ajouter un lien YouTube
            </button>
        </div>

        <!-- Section pour les fichiers vidéo MP4 -->
        <div class="multi-files-section">
            <div class="section-title">
                <h3><i class="fas fa-video"></i> Fichiers Vidéo MP4</h3>
            </div>
            
            <div id="video-container">
                <!-- Les éléments seront ajoutés ici dynamiquement -->
            </div>
            
            <button type="button" class="add-file-btn video-btn" onclick="addVideoField()">
                <i class="fas fa-plus-circle"></i> Ajouter une vidéo
            </button>
        </div>

        <div class="action-buttons">
            <button type="submit" class="submit-btn">
                <i class="fas fa-check"></i> Ajouter le chapitre
            </button>
            <a href="/e-learning-role-final/public/cours/voir/<?= $cours_id ?>" class="cancel-btn">
                <i class="fas fa-times"></i> Annuler
            </a>
        </div>
    </form>
</div>

<style>
    .admin-form-container {
        max-width: 900px;
        margin: 30px auto;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }
    
    .admin-form-container h2 {
        color: #2c3e50;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
        font-size: 28px;
        text-align: center;
    }
    
    .form-section {
        margin-bottom: 25px;
    }
    
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
    }
    
    input[type="text"], textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s;
    }
    
    input[type="text"]:focus, textarea:focus {
        border-color: #3B82F6;
        outline: none;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
    
    textarea {
        min-height: 120px;
        resize: vertical;
    }
    
    .multi-files-section {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
        border: 1px solid #e5e7eb;
    }
    
    .section-title {
        margin-bottom: 15px;
    }
    
    .section-title h3 {
        margin: 0;
        color: #2c3e50;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-title h3 i {
        color: #3B82F6;
    }

    .fab.fa-youtube {
        color: #FF0000;
    }
    
    .fas.fa-video {
        color: #00B894;
    }
    
    .add-file-btn {
        background-color: #3B82F6;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background-color 0.3s;
        width: 100%;
        margin-top: 15px;
    }
    
    .add-file-btn:hover {
        background-color: #2563EB;
    }
    
    .yt-btn {
        background-color: #FF0000;
    }
    
    .yt-btn:hover {
        background-color: #CC0000;
    }
    
    .video-btn {
        background-color: #00B894;
    }
    
    .video-btn:hover {
        background-color: #009B7D;
    }
    
    .file-input-card {
        background-color: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        border: 1px solid #e5e7eb;
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .file-input-card input {
        flex: 1;
        margin: 0;
    }
    
    .remove-file-btn {
        position: absolute;
        right: -12px;
        top: -12px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background-color: #EF4444;
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: background-color 0.2s;
        visibility: hidden; /* Masqué par défaut */
    }
    
    .remove-file-btn:hover {
        background-color: #DC2626;
    }
    
    .youtube-input {
        padding-left: 40px !important;
        background-image: url('https://www.youtube.com/favicon.ico');
        background-repeat: no-repeat;
        background-position: 10px center;
        background-size: 20px;
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }
    
    .submit-btn, .cancel-btn {
        flex: 1;
        padding: 14px 20px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .submit-btn {
        background-color: #4CAF50;
        color: white;
        border: none;
    }
    
    .submit-btn:hover {
        background-color: #3e8e41;
        transform: translateY(-2px);
    }
    
    .cancel-btn {
        background-color: #f5f5f5;
        color: #555;
        border: 1px solid #ddd;
    }
    
    .cancel-btn:hover {
        background-color: #ebebeb;
    }
    
    /* Pour les petits écrans */
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<script>
    // Initialiser les conteneurs
    document.addEventListener('DOMContentLoaded', function() {
        addPdfField();
        addYoutubeField();
        addVideoField();
    });

    function addPdfField() {
        const container = document.getElementById('pdf-container');
        const card = document.createElement('div');
        card.className = 'file-input-card';
        
        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'pdfs[]';
        input.accept = 'application/pdf';
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-file-btn';
        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
        removeBtn.onclick = function() { removeCard(this); };
        
        card.appendChild(input);
        card.appendChild(removeBtn);
        container.appendChild(card);
        
        // Mettre à jour la visibilité des boutons après ajout
        updateRemoveButtonsVisibility(container);
    }
    
    function addYoutubeField() {
        const container = document.getElementById('youtube-container');
        const card = document.createElement('div');
        card.className = 'file-input-card';
        
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'youtube_links[]';
        input.placeholder = 'https://www.youtube.com/watch?v=...';
        input.className = 'youtube-input';
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-file-btn';
        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
        removeBtn.onclick = function() { removeCard(this); };
        
        card.appendChild(input);
        card.appendChild(removeBtn);
        container.appendChild(card);
        
        // Mettre à jour la visibilité des boutons après ajout
        updateRemoveButtonsVisibility(container);
    }
    
    function addVideoField() {
        const container = document.getElementById('video-container');
        const card = document.createElement('div');
        card.className = 'file-input-card';
        
        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'videos[]';
        input.accept = 'video/mp4';
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-file-btn';
        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
        removeBtn.onclick = function() { removeCard(this); };
        
        card.appendChild(input);
        card.appendChild(removeBtn);
        container.appendChild(card);
        
        // Mettre à jour la visibilité des boutons après ajout
        updateRemoveButtonsVisibility(container);
    }
    
    function removeCard(button) {
        const card = button.parentElement;
        const container = card.parentElement;
        
        // Ne pas supprimer s'il ne reste qu'un seul élément
        if (container.children.length > 1) {
            container.removeChild(card);
            
            // Mettre à jour la visibilité des boutons après suppression
            updateRemoveButtonsVisibility(container);
        }
    }
    
    // Fonction pour mettre à jour la visibilité des boutons de suppression
    function updateRemoveButtonsVisibility(container) {
        const cards = container.querySelectorAll('.file-input-card');
        const removeButtons = container.querySelectorAll('.remove-file-btn');
        
        // Si nous avons plus d'un élément, afficher tous les boutons de suppression
        if (cards.length > 1) {
            removeButtons.forEach(btn => {
                btn.style.visibility = 'visible';
            });
        } else {
            // Sinon, masquer tous les boutons de suppression
            removeButtons.forEach(btn => {
                btn.style.visibility = 'hidden';
            });
        }
    }
</script>