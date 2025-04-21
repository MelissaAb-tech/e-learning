<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Learning - Accueil</title>
    <link rel="stylesheet" href="/e-learning-role-final/public/style/home.css">
    <link rel="stylesheet" href="/e-learning-role-final/public/style/cours-card.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>E-Learning Platform</h1>
        </div>
        <nav>
            <ul>
                <li><a href="/e-learning-role-final/public/">Accueil</a></li>
                <li><a href="/e-learning-role-final/public/login">Connexion</a></li>
                <li><a href="/e-learning-role-final/public/register">Inscription</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h2>Apprenez à votre rythme</h2>
            <p>Découvrez nos cours en ligne et développez vos compétences</p>
            <a href="#courses" class="btn">Voir les cours</a>
        </div>
    </section>

    <section id="courses" class="courses">
        <h2>Nos cours</h2>
        <div class="course-container">
            <?php foreach ($cours as $c): ?>
                <div class="course-box">
                    <img src="/e-learning-role-final/public/images/<?= $c['image'] ?>" class="course-img" alt="Image du cours">
                    <div class="course-info">
                        <span class="course-badge"><?= $c['niveau'] ?> • <?= $c['duree'] ?></span>
                        <h3><?= $c['nom'] ?></h3>
                        <p class="prof"><?= $c['professeur'] ?></p>
                        <p><?= nl2br(substr($c['contenu'], 0, 150)) ?>...</p>
                        <a href="/e-learning-role-final/public/login" class="btn-course">Se connecter pour accéder</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="features">
        <h2>Pourquoi choisir notre plateforme ?</h2>
        <div class="features-container">
            <div class="feature">
                <div class="feature-icon">📚</div>
                <h3>Cours variés</h3>
                <p>Accédez à une large bibliothèque de cours dans différents domaines</p>
            </div>
            <div class="feature">
                <div class="feature-icon">🎓</div>
                <h3>Enseignants qualifiés</h3>
                <p>Apprenez avec des professeurs expérimentés et passionnés</p>
            </div>
            <div class="feature">
                <div class="feature-icon">🏆</div>
                <h3>Certifications</h3>
                <p>Obtenez des certificats pour valider vos compétences</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>E-Learning Platform</h3>
                <p>Votre plateforme d'apprentissage en ligne</p>
            </div>
            <div class="footer-section">
                <h3>Liens rapides</h3>
                <ul>
                    <li><a href="/e-learning-role-final/public/">Accueil</a></li>
                    <li><a href="/e-learning-role-final/public/login">Connexion</a></li>
                    <li><a href="/e-learning-role-final/public/register">Inscription</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: contact@elearning.com</p>
                <p>Téléphone: +33 1 23 45 67 89</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 E-Learning Platform. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>