<link rel="stylesheet" href="/e-learning-role-final/public/style/mon-compte.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="account-page-container">

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success-message">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success_message'] ?>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error_message'] ?>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

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
                <label for="fonction">Profession :</label>
                <select id="fonction" name="fonction" required>
                    <option value="Étudiant" <?= ($user['fonction'] ?? '') == 'Étudiant' ? 'selected' : '' ?>>Étudiant</option>
                    <option value="Enseignant" <?= ($user['fonction'] ?? '') == 'Enseignant' ? 'selected' : '' ?>>Enseignant</option>
                    <option value="Professionnel" <?= ($user['fonction'] ?? '') == 'Professionnel' ? 'selected' : '' ?>>Professionnel</option>
                    <option value="Entrepreneur" <?= ($user['fonction'] ?? '') == 'Entrepreneur' ? 'selected' : '' ?>>Entrepreneur</option>
                    <option value="Retraité" <?= ($user['fonction'] ?? '') == 'Retraité' ? 'selected' : '' ?>>Retraité</option>
                    <option value="Demandeur d'emploi" <?= ($user['fonction'] ?? '') == 'Demandeur d\'emploi' ? 'selected' : '' ?>>Demandeur d'emploi</option>
                    <option value="Autre" <?= ($user['fonction'] ?? '') == 'Autre' ? 'selected' : '' ?>>Autre</option>
                </select>
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

            <div class="info-row">
                <label for="new_password">Nouveau mot de passe :</label>
                <input type="password" id="new_password" name="new_password" placeholder="Laissez vide pour conserver le mot de passe actuel">
            </div>

            <div class="info-row">
                <label for="confirm_password">Confirmer mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmer le nouveau mot de passe">
            </div>
            <div class="info-row">
                <label for="photo_profil">Photo de profil :</label>
                <input type="file" id="photo_profil" name="photo_profil" accept="image/*">
            </div>

            <div class="account-actions">
                <button type="submit" class="btn btn-submit">Mettre à jour</button>
                <a href="/e-learning-role-final/public/etudiant/dashboard" class="btn btn-secondary" style="display: inline-block; margin-top: 10px; background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; text-align: center;">Retour aux cours</a>
            </div>
        </div>
    </form>
</div>