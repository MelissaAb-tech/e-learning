<html>

<head>
    <title>Inscription</title>
    <link rel="stylesheet" href="/e-learning-role-final/public/style/register.css">
</head>

<body>
    
    <form method="POST" action="/e-learning-role-final/public/register" enctype="multipart/form-data">
        <div class="top-form">
            <div style="font-size:30px;font-weight:bold;">Créer un compte</div>
            <a class="back-btn" href="/e-learning-role-final/public/">Accueil</a>    
        </div>
        <hr>
        
        <div class="title-input">Photo de profil :</div>    
        <input type="file" name="photo_profil" accept="image/*"><br>
        <div class="title-input">Prénom :</div>
        <input type="text" name="prenom" placeholder="Prénom" required><br>
        <div class="title-input">Nom :</div>
        <input type="text" name="nom" placeholder="Nom" required><br>
        <div class="title-input">Age :</div>
        <input type="number" name="age" placeholder="Âge" required style="margin-bottom:20px"><br>
        <hr>
        <div class="title-input" style="margin-top:20px">Profession :</div>
        <select name="fonction" required>
            <option value="" disabled selected>Sélectionnez votre profession</option>
            <option value="Étudiant">Étudiant</option>
            <option value="Enseignant">Enseignant</option>
            <option value="Professionnel">Professionnel</option>
            <option value="Entrepreneur">Entrepreneur</option>
            <option value="Retraité">Retraité</option>
            <option value="Demandeur d'emploi">Demandeur d'emploi</option>
            <option value="Autre">Autre</option>
        </select><br>
        <div class="title-input">Adresse :</div>
        <input type="text" name="adresse" placeholder="Adresse" required><br>
        <div class="title-input">Téléphone :</div>
        <input type="text" name="telephone" placeholder="Téléphone" required><br>
        <div class="title-input">Adresse mail :</div>
        <input type="email" name="email" placeholder="Email" required><br>
        <div class="title-input">Mot de passe :</div>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
            
        <button type="submit">S'inscrire</button>

        <p>Vous avez déjà un compte ? <a href="/e-learning-role-final/public/login">Connectez vous !</a></p>
    </form>
</body>

</html>