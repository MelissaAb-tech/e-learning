<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un sujet - <?= htmlspecialchars($cours['nom']) ?></title>
    <link rel="stylesheet" href="/e-learning-role-final/public/style/forum.css">
</head>
<body>
    <div class="forum-header">
        <h1>Créer un nouveau sujet</h1>
        <a href="/e-learning-role-final/public/forum/cours/<?= $cours['id'] ?>" class="btn-retour">Retour au forum</a>
    </div>

    <div class="forum-container">
        <div class="create-topic-form">
            <h2>Nouveau sujet dans le forum du cours : <?= htmlspecialchars($cours['nom']) ?></h2>
            
            <?php if (isset($error)): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST" action="/e-learning-role-final/public/forum/creer/<?= $cours['id'] ?>">
                <div class="form-group">
                    <label for="titre">Titre du sujet</label>
                    <input type="text" id="titre" name="titre" required>
                </div>
                
                <div class="form-group">
                    <label for="contenu">Contenu</label>
                    <textarea id="contenu" name="contenu" rows="10" required></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-submit">Créer le sujet</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>