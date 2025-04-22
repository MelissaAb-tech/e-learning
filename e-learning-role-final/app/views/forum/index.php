<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-dashboard.css">

<div class="admin-header">
    <h1>Forum du cours : <?= htmlspecialchars($cours['nom']) ?></h1>
    <a href="/e-learning-role-final/public/cours/voir/<?= $cours['id'] ?>" style="color: white; margin-top: 10px; display: inline-block;">Retour au cours</a>
</div>

<div style="max-width: 800px; margin: 50px auto; padding: 30px; background-color: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
    <img src="/e-learning-role-final/public/images/coming-soon.png" alt="En construction" style="max-width: 200px; margin-bottom: 20px; opacity: 0.7;">
    <h2>Cette fonctionnalité arrive bientôt !</h2>
    <p style="color: #666; margin-bottom: 20px;">
        <?= $message ?>
    </p>
    <a href="/e-learning-role-final/public/cours/voir/<?= $cours['id'] ?>" style="display: inline-block; padding: 10px 20px; background-color: #3B82F6; color: white; text-decoration: none; border-radius: 5px;">
        Retourner au cours
    </a>
</div>