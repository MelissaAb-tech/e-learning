<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Learning - Votre plateforme de formation en ligne</title>
    <link rel="stylesheet" href="/e-learning-role-final/public/style/home.css">
    <link rel="stylesheet" href="/e-learning-role-final/public/style/chatbot.css">
</head>

<body>
    <!-- Header with navigation -->
    <header>
        <div class="container header-content">
            <div class="logo">E-<span>Learning</span></div>
            <div class="auth-buttons">
                <a href="/e-learning-role-final/public/login" class="btn btn-login">Se connecter</a>
                <a href="/e-learning-role-final/public/register" class="btn btn-register">S'inscrire</a>
            </div>
        </div>
    </header>

    <!-- Hero section -->
    <section class="hero">
        <div class="container">
            <h1>Développez vos compétences en ligne</h1>
            <p>Notre plateforme d'apprentissage vous permet d'accéder à des cours de qualité, dispensés par des professeurs experts. Apprenez à votre rythme, suivez votre progression et obtenez des certifications reconnues.</p>
            <a href="/e-learning-role-final/public/register" class="btn btn-hero">Commencer dès maintenant</a>
        </div>
    </section>

    <!-- Features section -->
    <section class="features">
        <div class="container">
            <h2>Pourquoi choisir notre plateforme ?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📚</div>
                    <h3>Cours de qualité</h3>
                    <p>Accédez à une bibliothèque de cours variés, créés par des professionnels du domaine et régulièrement mis à jour.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3>Suivi personnalisé</h3>
                    <p>Suivez votre progression en temps réel et visualisez vos accomplissements grâce à notre système de suivi intégré.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💻</div>
                    <h3>Apprentissage flexible</h3>
                    <p>Étudiez où et quand vous voulez, sur tous vos appareils, avec un accès illimité aux contenus de formation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular courses section -->
    <section class="popular-courses">
        <div class="container">
            <h2>Nos cours les plus populaires</h2>
            <div class="courses-grid">
                <div class="course-card">
                    <div class="course-image">
                        <img src="/e-learning-role-final/public/images/ia.jpg" alt="Introduction à l'IA">
                    </div>
                    <div class="course-content">
                        <span class="course-badge">Débutant • 5 heures</span>
                        <h3>Introduction à l'IA</h3>
                        <p class="course-teacher">Prof. Dupont</p>
                        <p class="course-desc">Ce cours présente les bases de l'intelligence artificielle et ses applications dans le monde moderne.</p>
                        <a href="/e-learning-role-final/public/register" class="btn btn-course">S'inscrire pour accéder</a>
                    </div>
                </div>
                <div class="course-card">
                    <div class="course-image">
                        <img src="/e-learning-role-final/public/images/html.jpg" alt="Développement Web">
                    </div>
                    <div class="course-content">
                        <span class="course-badge">Difficile • 3 mois</span>
                        <h3>Développement Web</h3>
                        <p class="course-teacher">Prof. Martin</p>
                        <p class="course-desc">Apprenez à créer des sites web avec HTML, CSS, JavaScript et PHP. Un cours complet pour devenir développeur web.</p>
                        <a href="/e-learning-role-final/public/register" class="btn btn-course">S'inscrire pour accéder</a>
                    </div>
                </div>
                <div class="course-card">
                    <div class="course-image">
                        <img src="/e-learning-role-final/public/images/system.jpg" alt="Système">
                    </div>
                    <div class="course-content">
                        <span class="course-badge">Facile • 6 mois</span>
                        <h3>Administration Système</h3>
                        <p class="course-teacher">Prof. Dupont</p>
                        <p class="course-desc">Découvrez les fondamentaux de l'administration système et apprenez à gérer des infrastructures informatiques.</p>
                        <a href="/e-learning-role-final/public/register" class="btn btn-course">S'inscrire pour accéder</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section "Comment ça marche ?" -->
    <section class="how-it-works">
        <div class="container">
            <h2>Comment ça marche ?</h2>
            <div class="how-grid">
                <div class="how-step">
                    <div class="how-icon">📝</div>
                    <h3>1. Inscription</h3>
                    <p>Créez votre compte en quelques clics pour accéder à tous nos cours en ligne.</p>
                </div>
                <div class="how-step">
                    <div class="how-icon">📚</div>
                    <h3>2. Choisissez un cours</h3>
                    <p>Parcourez notre catalogue et inscrivez-vous aux formations qui correspondent à vos objectifs.</p>
                </div>
                <div class="how-step">
                    <div class="how-icon">🎓</div>
                    <h3>3. Apprenez à votre rythme</h3>
                    <p>Suivez vos cours où vous voulez, avancez à votre rythme et obtenez vos certifications.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- Section "Avis d'étudiants" -->
    <section class="student-reviews">
        <div class="container">
            <h2>Avis d'étudiants</h2>
            <div class="reviews-grid">
                <div class="review-card">
                    <p class="review-text">"La plateforme est vraiment intuitive et les cours sont super bien expliqués. J'ai pu apprendre le machine learning en quelques mois !"</p>
                    <div class="review-author">
                        <img src="/e-learning-role-final/public/images/dupont.jpg" alt="Étudiant 1">
                        <div>
                            <h4>Jean Dupont</h4>
                            <span>Data Analyst</span>
                        </div>
                    </div>
                </div>
                <div class="review-card">
                    <p class="review-text">"Très bonne expérience d'apprentissage. Les quiz m'ont beaucoup aidée à vérifier mes connaissances au fur et à mesure."</p>
                    <div class="review-author">
                        <img src="/e-learning-role-final/public/images/martin.jpg" alt="Étudiant 2">
                        <div>
                            <h4>Sarah Martin</h4>
                            <span>Étudiante en Informatique</span>
                        </div>
                    </div>
                </div>
                <div class="review-card">
                    <p class="review-text">"J'ai adoré le format flexible, j'ai pu suivre les cours à mon rythme malgré mon travail à plein temps."</p>
                    <div class="review-author">
                        <img src="/e-learning-role-final/public/images/olivier.jpg" alt="Étudiant 3">
                        <div>
                            <h4>Olivier Petit</h4>
                            <span>En reconversion professionnelle</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Section "FAQ" -->
    <section class="faq">
        <div class="container">
            <h2>Questions fréquentes</h2>
            <div class="faq-grid">
                <div class="faq-item">
                    <h3>Comment puis-je m'inscrire ?</h3>
                    <p>Il vous suffit de cliquer sur "S'inscrire" et de remplir le formulaire en quelques minutes.</p>
                </div>
                <div class="faq-item">
                    <h3>Les cours sont-ils gratuits ?</h3>
                    <p>Tous nos cours sont gratuits</p>
                </div>
                <div class="faq-item">
                    <h3>Puis-je apprendre depuis à mon rythme ?</h3>
                    <p>Oui, tous nos cours sont accessibles depuis votre ordinateur pour un apprentissage à votre rythme</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Chatbot-->
    <div id="chatbot-wrapper" class="chatbot-wrapper" data-role="etudiant">
        <div id="chatbot-container" class="chatbot-collapsed">
            <div class="chatbot-header">
                <div class="chatbot-title">
                    <div class="chatbot-avatar"></div>
                    <span>Assistant E-Learning</span>
                </div>
                <div class="chatbot-controls">
                    <button id="chatbot-minimize" aria-label="Réduire">_</button>
                    <button id="chatbot-close" aria-label="Fermer">x</button>
                </div>
            </div>
            <div class="chatbot-body">
                <div id="chatbot-messages"></div>
            </div>
            <div class="chatbot-suggestions" id="chatbot-suggestions"></div>
            <div class="chatbot-footer">
                <form id="chatbot-form">
                    <input type="text" id="chatbot-input" placeholder="Pose ta question" autocomplete="off">
                    <button type="submit" id="chatbot-submit">➤</button>
                </form>
            </div>
        </div>
        <button id="chatbot-toggle" class="chatbot-toggle" aria-label="Ouvrir l'assistant">💬</button>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2025 E-Learning. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="/e-learning-role-final/public/JS/chatbot.js"></script>
</body>

</html>