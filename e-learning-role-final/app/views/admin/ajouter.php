<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un cours</title>
    <link rel="stylesheet" href="/e-learning-role-final/public/style/admin-form.css">
</head>

<body>
    <form style="margin-top:50px" method="POST" enctype="multipart/form-data">
        <h2>Ajouter un cours</h2>

        <input type="text" name="nom" placeholder="Nom du cours" required>
        <input type="text" name="professeur" placeholder="Nom du professeur" required>
        <input type="text" name="niveau" placeholder="Niveau (Facile, Moyen, Difficile...)" required>
        <input type="text" name="duree" placeholder="Durée (ex : 4h 30min)" required>

        <label for="image">Image du cours :</label>
        <input type="file" name="image" accept="image/*" required>

        <textarea name="contenu" placeholder="Description du cours" required></textarea>
        <hr style="margin:15px 0px">
        <div style="margin-top: 20px;justify-content:space-between;display:flex;">
            <button type="submit">Ajouter</button>
            <a class="back-btn" href="/e-learning-role-final/public/admin/dashboard">Retour au dashboard</a>
        </div>
    </form>
</body>

</html>