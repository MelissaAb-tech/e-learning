<html>
<html>

<head>
    <title>Inscription</title>
    <link rel="stylesheet" href="/e-learning-role-final/public/style/register.css">
</head>

<body>
    <h2>Créer un compte</h2>
    <form method="POST" action="/e-learning-role-final/public/register" enctype="multipart/form-data">
        <input type="file" name="photo_profil" accept="image/*"><br>

        <input type="text" name="prenom" placeholder="Prénom" required><br>
        <input type="text" name="nom" placeholder="Nom" required><br>
        <input type="number" name="age" placeholder="Âge" required><br>
        
        <select name="fonction" required>
            <option value="" disabled selected>Sélectionnez votre occupation</option>
            <option value="Étudiant">Étudiant</option>
            <option value="Enseignant">Enseignant</option>
            <option value="Professionnel">Professionnel</option>
            <option value="Entrepreneur">Entrepreneur</option>
            <option value="Retraité">Retraité</option>
            <option value="Demandeur d'emploi">Demandeur d'emploi</option>
            <option value="Autre">Autre</option>
        </select><br>
        
        <input type="text" name="adresse" placeholder="Adresse" required><br>
        <input type="text" name="telephone" placeholder="Téléphone" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>

        <button type="submit">S'inscrire</button>
    </form>
</body>

</html>