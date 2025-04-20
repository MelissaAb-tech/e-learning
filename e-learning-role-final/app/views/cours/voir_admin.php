<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-form.css">

<div style="max-width: 800px; margin: auto; padding: 20px;">
    <h2><?= $cours['nom'] ?></h2>
    <p><strong>Professeur :</strong> <?= $cours['professeur'] ?></p>
    <p><strong>Niveau :</strong> <?= $cours['niveau'] ?> • <strong>Durée :</strong> <?= $cours['duree'] ?></p>
    <p><?= nl2br($cours['contenu']) ?></p>
    <hr>
    <h3>Chapitres du cours :</h3>

    <?php foreach ($chapitres as $chap): ?>
        <div style="margin: 10px 0; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
            <h4><?= htmlspecialchars($chap['titre']) ?></h4>
            <p><?= nl2br(htmlspecialchars($chap['description'])) ?></p>

            <?php if (!empty($chap['pdf'])): ?>
                <p>📄 <a href="/e-learning-role-final/public/pdfs/<?= $chap['pdf'] ?>" target="_blank">Voir le PDF</a></p>
            <?php endif; ?>

            <?php if (!empty($chap['video'])): ?>
                <?php if (str_contains($chap['video'], 'youtube.com')): ?>
                    <p>🎥 <a href="<?= $chap['video'] ?>" target="_blank">Voir la vidéo YouTube</a></p>
                <?php else: ?>
                    <video controls width="100%" style="max-width: 500px;">
                        <source src="/e-learning-role-final/public/videos/<?= $chap['video'] ?>" type="video/mp4">
                    </video>
                <?php endif; ?>
            <?php endif; ?>

            <p>
                <a href="/e-learning-role-final/public/admin/chapitre/supprimer/<?= $chap['id'] ?>/<?= $cours['id'] ?>" onclick="return confirm('Supprimer ce chapitre ?')">🗑️ Supprimer le chapitre</a>
            </p>
        </div>
    <?php endforeach; ?>
</div>