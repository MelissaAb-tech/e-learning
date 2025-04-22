<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat de réussite - <?= htmlspecialchars($cours['nom']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .actions {
            margin-bottom: 20px;
            width: 100%;
            max-width: 800px;
            display: flex;
            justify-content: space-between;
        }
        
        .btn {
            padding: 10px 15px;
            background-color: #3B82F6;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
        }
        
        .certificate {
            background-color: white;
            width: 100%;
            max-width: 800px;
            padding: 50px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            position: relative;
            overflow: hidden;
        }
        
        .certificate::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial" font-size="12" text-anchor="middle" fill="rgba(0,0,0,0.05)" transform="rotate(-45, 50, 50)">CERTIFICAT</text></svg>');
            opacity: 0.5;
        }
        
        .certificate-header {
            text-align: center;
            border-bottom: 3px double #ccc;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }
        
        .certificate-title {
            font-size: 28px;
            color: #2c3e50;
            margin: 0;
            padding: 0;
        }
        
        .certificate-subtitle {
            font-size: 18px;
            color: #7f8c8d;
            margin: 10px 0 0;
        }
        
        .certificate-content {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .certificate-student {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 30px 0;
            padding: 10px;
            border-bottom: 1px solid #eee;
            display: inline-block;
        }
        
        .certificate-course {
            font-size: 20px;
            color: #2c3e50;
            margin: 20px 0;
        }
        
        .certificate-date {
            margin: 30px 0;
            font-style: italic;
            color: #7f8c8d;
        }
        
        .certificate-signature {
            display: flex;
            justify-content: space-around;
            margin-top: 60px;
        }
        
        .signature-block {
            text-align: center;
        }
        
        .signature-line {
            width: 200px;
            border-top: 1px solid #333;
            margin: 10px auto;
        }
        
        .signature-name {
            font-weight: bold;
        }
        
        .signature-title {
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .certificate-seal {
            position: absolute;
            bottom: 70px;
            right: 70px;
            opacity: 0.8;
            width: 120px;
            height: 120px;
        }
        
        @media print {
            body {
                background: none;
                padding: 0;
            }
            
            .actions {
                display: none;
            }
            
            .certificate {
                box-shadow: none;
                border: 1px solid #ccc;
                margin: 0;
                padding: 40px;
            }
        }
    </style>
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