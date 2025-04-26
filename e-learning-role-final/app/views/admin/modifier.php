<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-form.css">

<div class="admin-form-container">
    <h2>Modifier un cours</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="nom">Nom du cours</label>
        <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($cours['nom']) ?>" required>
        
        <label for="professeur">Nom du professeur</label>
        <input type="text" name="professeur" id="professeur" value="<?= htmlspecialchars($cours['professeur']) ?>" required>
        
        <label for="niveau">Niveau</label>
        <input type="text" name="niveau" id="niveau" value="<?= htmlspecialchars($cours['niveau']) ?>" required placeholder="Facile, Moyen, Difficile...">
        
        <label for="duree">Durée</label>
        <input type="text" name="duree" id="duree" value="<?= htmlspecialchars($cours['duree']) ?>" required placeholder="ex : 4h 30min">

        <label for="image">Image actuelle :</label>
        <?php if(!empty($cours['image'])): ?>
            <div style="margin-bottom: 10px;">
                <img src="/e-learning-role-final/public/images/<?= htmlspecialchars($cours['image']) ?>" alt="Image du cours" style="max-width: 200px; max-height: 150px;">
                <input type="hidden" name="image" value="<?= htmlspecialchars($cours['image']) ?>">
            </div>
        <?php endif; ?>
        
        <label for="nouvelle_image">Nouvelle image (optionnel) :</label>
        <input type="file" name="nouvelle_image" id="nouvelle_image" accept="image/*">

        <label for="contenu">Description du cours</label>
        <textarea name="contenu" id="contenu" required><?= htmlspecialchars($cours['contenu']) ?></textarea>
        <hr style="margin:15px 0px">
        <div style="margin-top: 20px;justify-content:space-between;display:flex;">
            <button class="submitBtn" type="submit">Modifier</button>
            <a href="/e-learning-role-final/public/admin/dashboard" style="background-color: #6c757d; color: white; padding: 12px 15px; text-decoration: none; border-radius: 5px; font-weight: 500;">Retour au tableau de bord</a>
        </div>
    </form>
</div>

<link rel="stylesheet" href="/e-learning-role-final/public/style/modifier.css">