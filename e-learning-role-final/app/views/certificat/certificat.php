<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat de réussite - <?= htmlspecialchars($cours['nom']) ?></title>
    <link rel="stylesheet" href="/e-learning-role-final/public/style/certificat.css">

</head>

<body>
    <div class="actions">
        <a href="/e-learning-role-final/public/cours/voir/<?= $cours['id'] ?>" class="btn">Retour au cours</a>
        <button onclick="window.print()" class="btn">Imprimer le certificat</button>
    </div>

    <div class="certificate">
        <div class="certificate-header">
            <h1 class="certificate-title">CERTIFICAT DE RÉUSSITE</h1>
            <p class="certificate-subtitle">E-Learning - Formation en ligne</p>
        </div>

        <div class="certificate-content">
            <p>Ce certificat atteste que</p>
            <div class="certificate-student">
                <?= htmlspecialchars($user['prenom'] ?? '') ?> <?= htmlspecialchars($user['nom']) ?>
            </div>
            <p>a complété avec succès le cours</p>
            <div class="certificate-course">
                "<?= htmlspecialchars($cours['nom']) ?>"
            </div>
            <p>avec tous les chapitres et quiz validés à 100%.</p>

            <div class="certificate-date">
                Délivré le <?= $date ?>
            </div>
        </div>

        <div class="certificate-signature">
            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-name"><?= htmlspecialchars($cours['professeur']) ?></div>
                <div class="signature-title">Formateur</div>
            </div>

            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-name">E-Learning</div>
                <div class="signature-title">Directeur de la formation</div>
            </div>
        </div>

        <svg class="certificate-seal" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="45" stroke="#4CAF50" stroke-width="2" fill="none" />
            <circle cx="50" cy="50" r="40" stroke="#4CAF50" stroke-width="1" fill="none" />
            <text x="50" y="45" font-size="12" text-anchor="middle" fill="#4CAF50">CERTIFIÉ</text>
            <text x="50" y="60" font-size="10" text-anchor="middle" fill="#4CAF50">E-LEARNING</text>
        </svg>
    </div>
</body>

</html>