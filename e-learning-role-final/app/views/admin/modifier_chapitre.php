<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-form.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="admin-form-container">
    <h2>Modifier un chapitre</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-section">
            <label for="titre">Titre du chapitre</label>
            <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($chapitre['titre']) ?>" required>
        </div>

        <div class="form-section">
            <label for="description">Description</label>
            <textarea name="description" id="description" required><?= htmlspecialchars($chapitre['description']) ?></textarea>
        </div>

        <!-- Section pour les fichiers PDF existants -->
        <?php if (!empty($chapitre['pdfs'])): ?>
        <div class="multi-files-section">
            <div class="section-title">
                <h3><i class="fas fa-file-pdf"></i> Fichiers PDF existants</h3>
            </div>
            
            <div class="existing-files">
                <?php foreach ($chapitre['pdfs'] as $pdf): ?>
                    <div class="file-card" id="pdf-<?= $pdf['id'] ?>">
                        <div class="file-card-inner">
                            <div class="file-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="file-info">
                                <span class="file-name"><?= htmlspecialchars($pdf['pdf']) ?></span>
                            </div>
                            <div class="file-actions">
                                <a href="/e-learning-role-final/public/pdfs/<?= $pdf['pdf'] ?>" target="_blank" class="file-view">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="file-delete-btn" onclick="confirmDelete('pdf', <?= $pdf['id'] ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <!-- Champ caché qui sera mis à jour par JS -->
                                <input type="hidden" name="pdf_to_keep[<?= $pdf['id'] ?>]" id="keep-pdf-<?= $pdf['id'] ?>" value="1">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Section pour ajouter de nouveaux PDFs -->
        <div class="multi-files-section">
            <div class="section-title">
                <h3><i class="fas fa-file-pdf"></i> Ajouter des PDFs</h3>
            </div>
            
            <div id="pdf-container">
                <!-- Les éléments seront ajoutés ici dynamiquement -->
            </div>
            
            <button type="button" class="add-file-btn" onclick="addPdfField()">
                <i class="fas fa-plus-circle"></i> Ajouter un PDF
            </button>
        </div>

        <!-- Section pour les liens YouTube existants -->
        <?php
        $youtubeLinks = array_filter($chapitre['videos'], function($v) { return $v['est_youtube'] == 1; });
        if (!empty($youtubeLinks)):
        ?>
        <div class="multi-files-section">
            <div class="section-title">
                <h3><i class="fab fa-youtube"></i> Liens YouTube existants</h3>
            </div>
            
            <div class="existing-files">
                <?php foreach ($youtubeLinks as $video): ?>
                    <div class="file-card" id="video-<?= $video['id'] ?>">
                        <div class="file-card-inner">
                            <div class="file-icon youtube-icon">
                                <i class="fab fa-youtube"></i>
                            </div>
                            <div class="file-info">
                                <span class="file-name"><?= htmlspecialchars($video['video']) ?></span>
                            </div>
                            <div class="file-actions">
                                <a href="<?= $video['video'] ?>" target="_blank" class="file-view youtube-view">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="file-delete-btn" onclick="confirmDelete('video', <?= $video['id'] ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <!-- Champ caché qui sera mis à jour par JS -->
                                <input type="hidden" name="video_to_keep[<?= $video['id'] ?>]" id="keep-video-<?= $video['id'] ?>" value="1">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Section pour ajouter de nouveaux liens YouTube -->
        <div class="multi-files-section">
            <div class="section-title">
                <h3><i class="fab fa-youtube"></i> Ajouter des liens YouTube</h3>
            </div>
            
            <div id="youtube-container">
                <!-- Les éléments seront ajoutés ici dynamiquement -->
            </div>
            
            <button type="button" class="add-file-btn yt-btn" onclick="addYoutubeField()">
                <i class="fas fa-plus-circle"></i> Ajouter un lien YouTube
            </button>
        </div>

        <!-- Section pour les fichiers vidéo MP4 existants -->
        <?php
        $mp4Files = array_filter($chapitre['videos'], function($v) { return $v['est_youtube'] == 0; });
        if (!empty($mp4Files)):
        ?>
        <div class="multi-files-section">
            <div class="section-title">
                <h3><i class="fas fa-video"></i> Fichiers Vidéo MP4 existants</h3>
            </div>
            
            <div class="existing-files">
                <?php foreach ($mp4Files as $video): ?>
                    <div class="file-card" id="video-<?= $video['id'] ?>">
                        <div class="file-card-inner">
                            <div class="file-icon video-icon">
                                <i class="fas fa-video"></i>
                            </div>
                            <div class="file-info">
                                <span class="file-name"><?= htmlspecialchars($video['video']) ?></span>
                            </div>
                            <div class="file-actions">
                                <button type="button" class="file-delete-btn" onclick="confirmDelete('video', <?= $video['id'] ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <!-- Champ caché qui sera mis à jour par JS -->
                                <input type="hidden" name="video_to_keep[<?= $video['id'] ?>]" id="keep-video-<?= $video['id'] ?>" value="1">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Section pour ajouter de nouveaux fichiers vidéo MP4 -->
        <div class="multi-files-section">
            <div class="section-title">
                <h3><i class="fas fa-video"></i> Ajouter des fichiers vidéo MP4</h3>
            </div>
            
            <div id="video-container">
                <!-- Les éléments seront ajoutés ici dynamiquement -->
            </div>
            
            <button type="button" class="add-file-btn video-btn" onclick="addVideoField()">
                <i class="fas fa-plus-circle"></i> Ajouter une vidéo
            </button>
        </div>

        <!-- Modal de confirmation pour la suppression -->
        <div id="delete-modal" class="delete-modal">
            <div class="delete-modal-content">
                <h3>Confirmer la suppression</h3>
                <p>Êtes-vous sûr de vouloir supprimer ce fichier ?</p>
                <div class="delete-modal-buttons">
                    <button type="button" onclick="closeDeleteModal()">Annuler</button>
                    <button type="button" id="confirm-delete-btn" class="delete-btn">Supprimer</button>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button type="submit" class="submit-btn">
                <i class="fas fa-check"></i> Enregistrer les modifications
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
        margin-left: 5px;
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
    
    .existing-files {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .file-card {
        background-color: white;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .file-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .file-card.marked-for-deletion {
        opacity: 0.5;
        background-color: #feedee;
        border-color: #EF4444;
    }
    
    .file-card-inner {
        padding: 15px;
        display: flex;
        align-items: center;
    }
    
    .file-icon {
        width: 40px;
        height: 40px;
        background-color: #EBF5FF;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        flex-shrink: 0;
    }
    
    .file-icon i {
        font-size: 20px;
        color: #3B82F6;
    }
    
    .youtube-icon {
        background-color: #FFEAEB;
    }
    
    .youtube-icon i {
        color: #FF0000;
    }
    
    .video-icon {
        background-color: #E6FFF9;
    }
    
    .video-icon i {
        color: #00B894;
    }
    
    .file-info {
        flex: 1;
        overflow: hidden;
    }
    
    .file-name {
        display: block;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #4B5563;
    }
    
    .file-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-left: 10px;
    }
    
    .file-view {
        color: #3B82F6;
        font-size: 16px;
        text-decoration: none;
    }
    
    .youtube-view {
        color: #FF0000;
    }
    
    .file-delete-btn {
        background: none;
        border: none;
        color: #EF4444;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: color 0.2s;
    }
    
    .file-delete-btn:hover {
        color: #B91C1C;
    }

    /* Modal de confirmation de suppression */
    .delete-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }
    
    .delete-modal-content {
        background-color: white;
        padding: 25px;
        border-radius: 10px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .delete-modal-content h3 {
        margin-top: 0;
        color: #2c3e50;
    }
    
    .delete-modal-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }
    
    .delete-modal-buttons button {
        padding: 8px 16px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
    }
    
    .delete-modal-buttons button:first-child {
        background-color: #e2e8f0;
        color: #4a5568;
    }
    
    .delete-modal-buttons .delete-btn {
        background-color: #EF4444;
        color: white;
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
        
        .existing-files {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    // Variables pour stocker l'ID et le type de l'élément à supprimer
    let currentDeleteType = '';
    let currentDeleteId = null;

    // Initialiser les conteneurs
    document.addEventListener('DOMContentLoaded', function() {
        addPdfField();
        addYoutubeField();
        addVideoField();
        
        // Initialiser la visibilité des boutons dans chaque conteneur
        updateRemoveButtonsVisibility(document.getElementById('pdf-container'));
        updateRemoveButtonsVisibility(document.getElementById('youtube-container'));
        updateRemoveButtonsVisibility(document.getElementById('video-container'));
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
    
    // Fonction pour ouvrir le modal de confirmation
    function confirmDelete(type, id) {
        // Mémoriser le type et l'ID pour l'action de suppression
        currentDeleteType = type;
        currentDeleteId = id;
        
        // Afficher le modal
        document.getElementById('delete-modal').style.display = 'flex';
        
        // Configurer le bouton de confirmation
        document.getElementById('confirm-delete-btn').onclick = function() {
            markForDeletion(currentDeleteType, currentDeleteId);
            closeDeleteModal();
        };
    }
    
    // Fonction pour fermer le modal
    function closeDeleteModal() {
        document.getElementById('delete-modal').style.display = 'none';
    }
    
    // Fonction pour marquer un fichier comme à supprimer
    function markForDeletion(type, id) {
        // Mettre à jour le champ caché pour indiquer que ce fichier doit être supprimé
        const keepField = document.getElementById(`keep-${type}-${id}`);
        if (keepField) {
            keepField.value = "0";
        }
        
        // Ajouter une classe visuelle pour indiquer que le fichier sera supprimé
        const fileCard = document.getElementById(`${type}-${id}`);
        if (fileCard) {
            fileCard.classList.add('marked-for-deletion');
            
            // Ajouter un bouton pour annuler la suppression
            const fileActions = fileCard.querySelector('.file-actions');
            
            // Vérifier si un bouton d'annulation existe déjà
            const existingUndoBtn = fileCard.querySelector('.undo-delete-btn');
            if (!existingUndoBtn) {
                const undoBtn = document.createElement('button');
                undoBtn.type = 'button';
                undoBtn.className = 'undo-delete-btn';
                undoBtn.innerHTML = '<i class="fas fa-undo"></i>';
                undoBtn.title = 'Annuler la suppression';
                undoBtn.onclick = function() {
                    undoMarkForDeletion(type, id);
                };
                
                fileActions.appendChild(undoBtn);
            }
        }
    }
    
    // Fonction pour annuler le marquage pour suppression
    function undoMarkForDeletion(type, id) {
        // Réinitialiser le champ caché
        const keepField = document.getElementById(`keep-${type}-${id}`);
        if (keepField) {
            keepField.value = "1";
        }
        
        // Retirer la classe visuelle
        const fileCard = document.getElementById(`${type}-${id}`);
        if (fileCard) {
            fileCard.classList.remove('marked-for-deletion');
            
            // Supprimer le bouton d'annulation
            const undoBtn = fileCard.querySelector('.undo-delete-btn');
            if (undoBtn) {
                undoBtn.remove();
            }
        }
    }
    
    // Fermer le modal si l'utilisateur clique en dehors
    window.onclick = function(event) {
        const modal = document.getElementById('delete-modal');
        if (event.target === modal) {
            closeDeleteModal();
        }
    };
</script>