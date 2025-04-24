<link rel="stylesheet" href="/e-learning-role-final/public/style/ajout-etudiant.css">

<div class="form-container">
    <h2>Modifier l'étudiant</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($etudiant['prenom'] ?? '') ?>" required>

        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($etudiant['nom'] ?? '') ?>" required>

        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($etudiant['email'] ?? '') ?>" required>

        <label>Mot de passe :</label>
        <input type="password" name="password" placeholder="Mot de passe">

        <label>Âge :</label>
        <input type="number" name="age" value="<?= htmlspecialchars($etudiant['age'] ?? '') ?>" required>

        <label>Adresse :</label>
        <input type="text" name="adresse" value="<?= htmlspecialchars($etudiant['adresse'] ?? '') ?>" required>

        <label>Fonction :</label>
        <input type="text" name="fonction" value="<?= htmlspecialchars($etudiant['fonction'] ?? '') ?>" required>

        <label>Téléphone :</label>
        <input type="tel" name="telephone" value="<?= htmlspecialchars($etudiant['telephone'] ?? '') ?>" required>

        <label>Photo de profil actuelle :</label>
        <?php if (!empty($etudiant['photo_profil'])): ?>
            <div style="margin-bottom: 10px;">
                <img src="/e-learning-role-final/public/images/<?= htmlspecialchars($etudiant['photo_profil']) ?>" alt="Photo de profil" style="max-width: 100px; max-height: 100px;">
            </div>
        <?php else: ?>
            <p>Aucune photo de profil</p>
        <?php endif; ?>

        <label>Nouvelle photo de profil (optionnel) :</label>
        <input type="file" name="photo_profil" accept="image/*">

        <button type="submit">Mettre à jour</button>
    </form>
    
    <div style="text-align: center; margin-top: 20px;">
        <a href="/e-learning-role-final/public/admin/dashboard" style="display: inline-block; background-color: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: 500;">Retour au tableau de bord</a>
    </div>
</div>