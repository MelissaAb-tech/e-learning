<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-form.css">

<div class="admin-form-container">
    <h2>Modifier un chapitre</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="titre">Titre du chapitre</label>
        <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($chapitre['titre']) ?>" required>

        <label for="description">Description</label>
        <textarea name="description" id="description" required><?= htmlspecialchars($chapitre['description']) ?></textarea>

        <label for="pdf">Fichier PDF</label>
        <input type="file" name="pdf" id="pdf" accept="application/pdf">
        <?php if(!empty($chapitre['pdf'])): ?>
            <p>PDF actuel: <?= htmlspecialchars($chapitre['pdf']) ?></p>
        <?php endif; ?>

        <label for="video">Lien YouTube</label>
        <input type="text" name="video" id="video" placeholder="https://www.youtube.com/watch?v=..." value="<?= str_contains($chapitre['video'] ?? '', 'youtube.com') || str_contains($chapitre['video'] ?? '', 'youtu.be') ? htmlspecialchars($chapitre['video']) : '' ?>" class="youtube-input">
        
        <label for="video_file">Ou fichier vidéo MP4</label>
        <input type="file" name="video_file" id="video_file" accept="video/mp4">
        <?php if(!empty($chapitre['video']) && !(str_contains($chapitre['video'], 'youtube.com') || str_contains($chapitre['video'], 'youtu.be'))): ?>
            <p>Vidéo actuelle: <?= htmlspecialchars($chapitre['video']) ?></p>
        <?php endif; ?>

        <button type="submit">Modifier le chapitre</button>
        <a href="/e-learning-role-final/public/cours/voir/<?= $cours_id ?>" style="display: inline-block; background-color: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: 500; margin-top: 10px;">Retour au cours</a>
    </form>
</div>

<style>
    .youtube-input {
        background-image: url('https://www.youtube.com/favicon.ico');
        background-repeat: no-repeat;
        background-position: 10px center;
        background-size: 20px;
        padding-left: 40px !important;
    }
</style>