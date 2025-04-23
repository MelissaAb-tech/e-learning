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

        <label for="video">Vidéo (YouTube ou fichier MP4)</label>
        <input type="text" name="video" placeholder="Lien YouTube OU nom fichier" value="<?= htmlspecialchars($chapitre['video'] ?? '') ?>">
        <input type="file" name="video_file" accept="video/mp4">
        <?php if(!empty($chapitre['video'])): ?>
            <p>Vidéo actuelle: <?= htmlspecialchars($chapitre['video']) ?></p>
        <?php endif; ?>

        <button type="submit">Modifier le chapitre</button>
        <a href="/e-learning-role-final/public/cours/voir/<?= $cours_id ?>" style="display: inline-block; background-color: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: 500; margin-top: 10px;">Retour au cours</a>
    </form>
</div>