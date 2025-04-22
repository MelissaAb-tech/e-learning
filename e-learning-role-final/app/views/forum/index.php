<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum - <?= htmlspecialchars($cours['nom']) ?></title>
    <link rel="stylesheet" href="/e-learning-role-final/public/style/forum.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="forum-header">
        <h1>Forum du cours : <?= htmlspecialchars($cours['nom']) ?></h1>
        <a href="/e-learning-role-final/public/cours/voir/<?= $cours['id'] ?>" class="btn-retour">Retour au cours</a>
    </div>

    <div class="forum-container">
        <div class="forum-actions">
            <a href="/e-learning-role-final/public/forum/creer/<?= $cours['id'] ?>" class="btn-creer">
                <i class="fas fa-plus-circle"></i> Créer un nouveau sujet
            </a>
        </div>

        <?php if (empty($topics)): ?>
            <div class="no-topics">
                <p>Aucun sujet n'a encore été créé dans ce forum.</p>
                <p>Soyez le premier à lancer une discussion!</p>
            </div>
        <?php else: ?>
            <div class="topics-list">
                <?php foreach ($topics as $topic): ?>
                    <div class="topic-item">
                        <div class="topic-info">
                            <h3 class="topic-title">
                                <a href="/e-learning-role-final/public/forum/voir/<?= $topic['id'] ?>">
                                    <?= htmlspecialchars($topic['titre']) ?>
                                </a>
                            </h3>
                            <div class="topic-meta">
                                <span class="topic-author">Par <?= htmlspecialchars($topic['auteur_nom']) ?></span>
                                <span class="topic-date">le <?= date('d/m/Y à H:i', strtotime($topic['date_creation'])) ?></span>
                                <span class="topic-replies"><?= $topic['nb_reponses'] ?> réponse(s)</span>
                            </div>
                        </div>
                        <div class="topic-actions">
                            <?php if (isset($_SESSION['user']) && ($_SESSION['user']['id'] == $topic['user_id'] || $_SESSION['user']['role'] == 'admin')): ?>
                                <a href="/e-learning-role-final/public/forum/supprimer_topic/<?= $topic['id'] ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce sujet?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>