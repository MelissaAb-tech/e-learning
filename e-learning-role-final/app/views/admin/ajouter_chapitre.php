<h2>Ajouter un chapitre</h2>
<form method="POST" enctype="multipart/form-data">
    <label>Titre du chapitre :</label>
    <input type="text" name="titre" required><br>

    <label>Description :</label>
    <textarea name="description" required></textarea><br>

    <label>PDF :</label>
    <input type="file" name="pdf" accept="application/pdf"><br>

    <label>Vidéo (.mp4) :</label>
    <input type="file" name="video" accept="video/mp4"><br><br>

    <button type="submit">Ajouter le chapitre</button>
</form>