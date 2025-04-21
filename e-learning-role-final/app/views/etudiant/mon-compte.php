<link rel="stylesheet" href="/e-learning-role-final/public/style/mon-compte.css">

<div class="account-page-container">
    <div class="profile-header">
        <div class="profile-img-container">
            <?php if ($user['photo_profil']): ?>
                <img src="/e-learning-role-final/public/images/<?= $user['photo_profil'] ?>" alt="Photo de profil" class="profile-img">
            <?php else: ?>
                <div class="default-img">Aucune photo</div>
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <h2><?= htmlspecialchars($user['prenom'] ?? 'Nom inconnu') ?> <?= htmlspecialchars($user['nom'] ?? 'Non renseigné') ?></h2>
            <p>Email : <?= htmlspecialchars($user['email']) ?></p>
        </div>
    </div>

    <form method="POST" action="/e-learning-role-final/public/etudiant/mon-compte/modifier" enctype="multipart/form-data">
        <div class="account-details">
            <div class="info-row">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom'] ?? '') ?>" required>
            </div>

            <div class="info-row">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom'] ?? '') ?>" required>
            </div>

            <div class="info-row">
                <label for="age">Âge :</label>
                <input type="number" id="age" name="age" value="<?= htmlspecialchars($user['age'] ?? '') ?>" required>
            </div>

            <div class="info-row">
                <label for="fonction">Fonction :</label>
                <input type="text" id="fonction" name="fonction" value="<?= htmlspecialchars($user['fonction'] ?? '') ?>" required>
            </div>

            <div class="info-row">
                <label for="adresse">Adresse :</label>
                <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($user['adresse'] ?? '') ?>" required>
            </div>

            <div class="info-row">
                <label for="telephone">Téléphone :</label>
                <input type="text" id="telephone" name="telephone" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>" required>
            </div>

            <div class="info-row">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
            </div>

            <!-- Ajouter le champ de photo de profil -->
            <div class="info-row">
                <label for="photo_profil">Photo de profil :</label>
                <input type="file" id="photo_profil" name="photo_profil" accept="image/*">
            </div>

            <div class="account-actions">
                <button type="submit" class="btn btn-submit">Mettre à jour</button>
            </div>
        </div>
    </form>
</div>