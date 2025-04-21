<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-form.css">

<div class="admin-form-container">
    <h2>Ajouter un chapitre</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="titre">Titre du chapitre</label>
        <input type="text" name="titre" id="titre" required>

        <label for="description">Description</label>
        <textarea name="description" id="description" required></textarea>

        <label for="pdf">Fichier PDF</label>
        <input type="file" name="pdf" id="pdf" accept="application/pdf">

        <label for="video">Vidéo (YouTube ou fichier MP4)</label>
        <input type="text" name="video" placeholder="Lien YouTube OU nom fichier">
        <input type="file" name="video_file" accept="video/mp4">

        <button type="submit">Ajouter le chapitre</button>
    </form>
</div>