<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-form.css">

<div class="admin-form-container">
    <h2>Modifier le quiz</h2>
    <p>Pour le cours: <strong><?= htmlspecialchars($cours['nom']) ?></strong></p>
    
    <?php if (isset($error)): ?>
        <div style="color: red; margin-bottom: 15px;"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <label for="titre">Titre du quiz</label>
        <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($quiz['titre']) ?>" required>
        
        <label for="description">Description (facultatif)</label>
        <textarea name="description" id="description" rows="4"><?= htmlspecialchars($quiz['description']) ?></textarea>
        
        <button type="submit">Mettre à jour</button>
        <a href="/e-learning-role-final/public/quiz/index/<?= $cours['id'] ?>" style="display: inline-block; margin-top: 10px;">Retour aux quiz</a>
    </form>
</div>