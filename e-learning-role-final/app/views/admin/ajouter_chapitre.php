<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-form.css">
<link rel="stylesheet" href="/e-learning-role-final/public/style/ajout_chapiter.css">
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

<script src="/e-learning-role-final/public/JS/ajout_chapitre.js"></script>