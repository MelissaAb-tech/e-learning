<link rel="stylesheet" href="/e-learning-role-final/public/style/ajout-etudiant.css">

<div class="form-container">
    <h2>Ajouter un étudiant</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Prénom :</label>
        <input type="text" name="prenom" required>

        <label>Nom :</label>
        <input type="text" name="nom" required>

        <label>Email :</label>
        <input type="email" name="email" required>

        <label>Mot de passe :</label>
        <input type="password" name="password" required>

        <label>Âge :</label>
        <input type="number" name="age" min="1" required>

        <label>Adresse :</label>
        <input type="text" name="adresse" required>

        <label>Occupation :</label>
        <select name="fonction" required>
            <option value="" disabled selected>Sélectionnez une occupation</option>
            <option value="Étudiant">Étudiant</option>
            <option value="Enseignant">Enseignant</option>
            <option value="Professionnel">Professionnel</option>
            <option value="Entrepreneur">Entrepreneur</option>
            <option value="Retraité">Retraité</option>
            <option value="Demandeur d'emploi">Demandeur d'emploi</option>
            <option value="Autre">Autre</option>
        </select>

        <label>Téléphone :</label>
        <input type="tel" name="telephone" required>

        <label>Photo de profil :</label>
        <input type="file" name="photo_profil" accept="image/*" required>

        <button type="submit">Créer le compte</button>
    </form>
    
    <div style="text-align: center; margin-top: 20px;">
        <a href="/e-learning-role-final/public/admin/dashboard" style="display: inline-block; background-color: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: 500;">Retour au tableau de bord</a>
    </div>
</div>