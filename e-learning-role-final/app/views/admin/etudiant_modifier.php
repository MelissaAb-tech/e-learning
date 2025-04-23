<link rel="stylesheet" href="/e-learning-role-final/public/style/ajout-etudiant.css">

<div class="form-container">
    <h2>Modifier l'étudiant</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($etudiant['prenom']) ?>" required>

        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($etudiant['nom']) ?>" required>

        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($etudiant['email']) ?>" required>

        <label>Mot de passe :</label>
        <input type="password" name="password" placeholder="Mot de passe">

        <label>Âge :</label>
        <input type="number" name="age" value="<?= htmlspecialchars($etudiant['age']) ?>" required>

        <label>Adresse :</label>
        <input type="text" name="adresse" value="<?= htmlspecialchars($etudiant['adresse']) ?>" required>

        <label>Fonction :</label>
        <input type="text" name="fonction" value="<?= htmlspecialchars($etudiant['fonction']) ?>" required>

        <label>Téléphone :</label>
        <input type="tel" name="telephone" value="<?= htmlspecialchars($etudiant['telephone']) ?>" required>

        <label>Photo de profil :</label>
        <input type="file" name="photo_profil" accept="image/*">

        <button type="submit">Mettre à jour</button>
    </form>

</div>