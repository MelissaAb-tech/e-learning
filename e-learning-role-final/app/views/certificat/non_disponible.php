<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-dashboard.css">

<div class="admin-header">
    <h1>Certificat pour le cours : <?= htmlspecialchars($cours['nom']) ?></h1>
    <a href="/e-learning-role-final/public/cours/voir/<?= $cours['id'] ?>" style="color: white; margin-top: 10px; display: inline-block;">Retour au cours</a>
</div>

<div style="max-width: 800px; margin: 50px auto; padding: 30px; background-color: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
    <div style="margin-bottom: 20px; font-size: 50px;">🏆</div>
    <h2 style="color: #e53e3e;">Certificat non disponible</h2>
    <p style="color: #666; margin-bottom: 20px;">
        Vous n'avez pas encore complété toutes les conditions requises pour obtenir votre certificat.
    </p>
    
    <div style="margin: 30px auto; max-width: 500px; text-align: left;">
        <h3>Conditions à remplir :</h3>
        
        <div style="margin: 15px 0;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span>Progression des chapitres :</span>
                <span><?= round($progression_chapitres) ?>% / 100%</span>
            </div>
            <div style="background: #e0e0e0; border-radius: 20px; height: 12px; overflow: hidden;">
                <div style="height: 100%; background: <?= $progression_chapitres == 100 ? '#4CAF50' : '#FF9800' ?>; width: <?= $progression_chapitres ?>%;"></div>
            </div>
        </div>
        
        <div style="margin: 15px 0;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span>Progression des quiz :</span>
                <span><?= round($progression_quiz) ?>% / 100%</span>
            </div>
            <div style="background: #e0e0e0; border-radius: 20px; height: 12px; overflow: hidden;">
                <div style="height: 100%; background: <?= $progression_quiz == 100 ? '#4CAF50' : '#FF9800' ?>; width: <?= $progression_quiz ?>%;"></div>
            </div>
        </div>
    </div>
    
    <a href="/e-learning-role-final/public/cours/voir/<?= $cours['id'] ?>" style="display: inline-block; padding: 10px 20px; background-color: #3B82F6; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px;">
        Retourner au cours
    </a>
</div>