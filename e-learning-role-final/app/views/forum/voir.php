<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($topic['titre']) ?> - Forum</title>
    <link rel="stylesheet" href="/e-learning-role-final/public/style/forum.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/e-learning-role-final/public/style/forum-extra.css">
</head>

<body>
    <div class="forum-header">
        <h1><?= htmlspecialchars($topic['titre']) ?></h1>
        <a href="/e-learning-role-final/public/forum/cours/<?= $cours['id'] ?>" class="btn-retour">Retour au forum</a>
    </div>

    <div class="forum-container">
        <div class="topic-message">
            <div class="message-header">
                <div class="message-author">Par <?= htmlspecialchars($topic['auteur_nom']) ?></div>
                <div class="message-date">le <?= date('d/m/Y à H:i', strtotime($topic['date_creation'])) ?></div>
            </div>
            <div class="message-content">
                <?= nl2br(htmlspecialchars($topic['contenu'])) ?>
            </div>
            <?php if (isset($_SESSION['user']) && ($_SESSION['user']['id'] == $topic['user_id'] || $_SESSION['user']['role'] == 'admin')): ?>
                <div class="message-actions">
                    <a href="/e-learning-role-final/public/forum/supprimer_topic/<?= $topic['id'] ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce sujet?')">
                        <i class="fas fa-trash"></i> Supprimer
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Réponses -->
        <div class="responses-section">
            <h2><?= count($reponses) ?> Réponse(s)</h2>

            <?php if (empty($reponses)): ?>
                <div class="no-responses">
                    <p>Aucune réponse pour le moment. Soyez le premier à répondre !</p>
                </div>
            <?php else: ?>
                <div class="responses-list">
                    <?php foreach ($reponses as $reponse): ?>
                        <div class="response-item">
                            <div class="message-header">
                                <div class="message-author">Par <?= htmlspecialchars($reponse['auteur_nom']) ?></div>
                                <div class="message-date">le <?= date('d/m/Y à H:i', strtotime($reponse['date_creation'])) ?></div>
                            </div>
                            <div class="message-content">
                                <?= nl2br(htmlspecialchars($reponse['contenu'])) ?>
                            </div>
                            <?php if (isset($_SESSION['user']) && ($_SESSION['user']['id'] == $reponse['user_id'] || $_SESSION['user']['role'] == 'admin')): ?>
                                <div class="message-actions">
                                    <a href="/e-learning-role-final/public/forum/supprimer_reponse/<?= $reponse['id'] ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Formulaire de réponse -->
        <div class="reply-form">
            <h2>Répondre</h2>

            <?php if (isset($error)): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="/e-learning-role-final/public/forum/repondre/<?= $topic['id'] ?>">
                <div class="form-group">
                    <textarea id="contenu" name="contenu" rows="5" required></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Envoyer la réponse</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>