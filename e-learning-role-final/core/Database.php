<?php
class Database
{
    private static $pdo;

    public static function connect()
    {
        if (!self::$pdo) {
            try {
                // D'abord, tenter de se connecter à MySQL sans spécifier de base de données
                $tempPdo = new PDO('mysql:host=localhost', 'root', '');
                $tempPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Vérifier si la base de données existe
                $result = $tempPdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'elearning'");

                // Si la base de données n'existe pas, la créer
                if ($result->rowCount() === 0) {
                    $tempPdo->exec("CREATE DATABASE elearning CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
                    $needInit = true;
                } else {
                    $needInit = false;
                }

                // Se connecter à la base de données
                self::$pdo = new PDO('mysql:host=localhost;dbname=elearning', 'root', '');
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Initialiser les tables si nécessaire
                if ($needInit) {
                    self::initDatabase();
                }

                // Vérifier si les tables pour fichiers multiples existent déjà
                self::createMultiFilesTables();
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    private static function createMultiFilesTables()
    {
        try {
            // Vérifier si la table chapitre_pdfs existe déjà
            $result = self::$pdo->query("SHOW TABLES LIKE 'chapitre_pdfs'");
            if ($result->rowCount() === 0) {
                // Créer la table pour les PDFs
                self::$pdo->exec("CREATE TABLE IF NOT EXISTS chapitre_pdfs (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    chapitre_id INT NOT NULL,
                    pdf VARCHAR(255) NOT NULL,
                    FOREIGN KEY (chapitre_id) REFERENCES chapitres(id) ON DELETE CASCADE
                )");

                // Migrer les données existantes
                self::$pdo->exec("INSERT INTO chapitre_pdfs (chapitre_id, pdf)
                    SELECT id, pdf FROM chapitres 
                    WHERE pdf IS NOT NULL AND pdf != ''");
            }

            // Vérifier si la table chapitre_videos existe déjà
            $result = self::$pdo->query("SHOW TABLES LIKE 'chapitre_videos'");
            if ($result->rowCount() === 0) {
                // Créer la table pour les vidéos
                self::$pdo->exec("CREATE TABLE IF NOT EXISTS chapitre_videos (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    chapitre_id INT NOT NULL,
                    video VARCHAR(255) NOT NULL,
                    est_youtube TINYINT(1) DEFAULT 0,
                    FOREIGN KEY (chapitre_id) REFERENCES chapitres(id) ON DELETE CASCADE
                )");

                // Migrer les données existantes
                self::$pdo->exec("INSERT INTO chapitre_videos (chapitre_id, video, est_youtube)
                    SELECT id, video, 
                    CASE 
                        WHEN video LIKE '%youtu.be%' OR video LIKE '%youtube.com%' THEN 1 
                        ELSE 0 
                    END 
                    FROM chapitres 
                    WHERE video IS NOT NULL AND video != ''");
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la création des tables de fichiers multiples: " . $e->getMessage();
        }
    }

    private static function initDatabase()
    {
        try {
            // Créer toutes les tables nécessaires
            self::createTables();

            // Insérer des données de démonstration
            self::insertDemoData();
        } catch (PDOException $e) {
            die("Erreur lors de l'initialisation de la base de données: " . $e->getMessage());
        }
    }

    private static function createTables()
    {
        // Création de la table users
        self::$pdo->exec("CREATE TABLE `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nom` varchar(100) NOT NULL,
            `email` varchar(100) NOT NULL,
            `password` varchar(255) NOT NULL,
            `role` enum('admin','etudiant') DEFAULT 'etudiant',
            `prenom` varchar(100) DEFAULT NULL,
            `age` int(11) DEFAULT NULL,
            `adresse` varchar(255) DEFAULT NULL,
            `telephone` varchar(20) DEFAULT NULL,
            `photo_profil` varchar(255) DEFAULT NULL,
            `fonction` varchar(100) DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        // Création de la table cours
        self::$pdo->exec("CREATE TABLE `cours` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nom` varchar(255) NOT NULL,
            `professeur` varchar(255) NOT NULL,
            `contenu` text NOT NULL,
            `niveau` varchar(50) DEFAULT NULL,
            `duree` varchar(50) DEFAULT NULL,
            `image` varchar(255) DEFAULT NULL,
            `pdf` varchar(255) DEFAULT NULL,
            `video` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        // Création de la table chapitres
        self::$pdo->exec("CREATE TABLE `chapitres` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `cours_id` int(11) NOT NULL,
            `titre` varchar(255) NOT NULL,
            `description` text DEFAULT NULL,
            `pdf` varchar(255) DEFAULT NULL,
            `video` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `cours_id` (`cours_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        // Création de la table chapitre_progression
        self::$pdo->exec("CREATE TABLE `chapitre_progression` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `chapitre_id` int(11) NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_progress` (`user_id`,`chapitre_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        // Ajouter les contraintes de clé étrangère
        self::$pdo->exec("ALTER TABLE `chapitres` ADD CONSTRAINT `chapitres_ibfk_1` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE");

        // Créer la table cours_inscriptions
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS `cours_inscriptions` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `cours_id` int(11) NOT NULL,
            `date_inscription` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            UNIQUE KEY `user_cours` (`user_id`,`cours_id`),
            KEY `user_id` (`user_id`),
            KEY `cours_id` (`cours_id`),
            CONSTRAINT `cours_inscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
            CONSTRAINT `cours_inscriptions_ibfk_2` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        // Création des tables de quiz
        self::createQuizTables();

        // Création des tables de forum
        self::createForumTables();

        // Création des tables de feedback
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS `feedbacks_generaux` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `etudiant_id` INT NOT NULL,
            `note` INT NOT NULL CHECK (note BETWEEN 1 AND 5),
            `commentaire` TEXT NOT NULL,
            `date_feedback` DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`etudiant_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        // Création de la table feedbacks_cours
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS `feedbacks_cours` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `cours_id` int(11) NOT NULL,
            `etudiant_id` int(11) NOT NULL,
            `note` int(11) NOT NULL CHECK (note BETWEEN 1 AND 5),
            `commentaire` text NOT NULL,
            `date_feedback` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            UNIQUE KEY `etudiant_cours` (`etudiant_id`,`cours_id`),
            KEY `cours_id` (`cours_id`),
            KEY `etudiant_id` (`etudiant_id`),
            CONSTRAINT `feedbacks_cours_ibfk_1` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE,
            CONSTRAINT `feedbacks_cours_ibfk_2` FOREIGN KEY (`etudiant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    }

    private static function createQuizTables()
    {
        // Table des quizzes
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS `quizzes` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `cours_id` int(11) NOT NULL,
            `titre` varchar(255) NOT NULL,
            `description` text DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `cours_id` (`cours_id`),
            CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        // Table des questions
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS `questions` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `quiz_id` int(11) NOT NULL,
            `texte` text NOT NULL,
            `type` enum('unique','multiple') NOT NULL DEFAULT 'unique',
            `ordre` int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            KEY `quiz_id` (`quiz_id`),
            CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        // Table des options de réponse
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS `options` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `question_id` int(11) NOT NULL,
            `texte` text NOT NULL,
            `est_correcte` tinyint(1) NOT NULL DEFAULT 0,
            `ordre` int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            KEY `question_id` (`question_id`),
            CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        // Table des tentatives de quiz
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS `tentatives_quiz` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `quiz_id` int(11) NOT NULL,
            `score` int(11) NOT NULL,
            `score_max` int(11) NOT NULL,
            `date_tentative` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`),
            KEY `quiz_id` (`quiz_id`),
            CONSTRAINT `tentatives_quiz_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
            CONSTRAINT `tentatives_quiz_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        // Table des réponses des étudiants
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS `reponses_etudiant` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `tentative_id` int(11) NOT NULL,
            `question_id` int(11) NOT NULL,
            `option_id` int(11) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `tentative_id` (`tentative_id`),
            KEY `question_id` (`question_id`),
            KEY `option_id` (`option_id`),
            CONSTRAINT `reponses_etudiant_ibfk_1` FOREIGN KEY (`tentative_id`) REFERENCES `tentatives_quiz` (`id`) ON DELETE CASCADE,
            CONSTRAINT `reponses_etudiant_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
            CONSTRAINT `reponses_etudiant_ibfk_3` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    }

    private static function createForumTables()
    {
        // Table des topics
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS `topics` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `cours_id` int(11) NOT NULL,
            `user_id` int(11) NOT NULL,
            `titre` varchar(255) NOT NULL,
            `contenu` text NOT NULL,
            `date_creation` datetime NOT NULL,
            PRIMARY KEY (`id`),
            KEY `cours_id` (`cours_id`),
            KEY `user_id` (`user_id`),
            CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE,
            CONSTRAINT `topics_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        // Table des réponses
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS `reponses` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `topic_id` int(11) NOT NULL,
            `user_id` int(11) NOT NULL,
            `contenu` text NOT NULL,
            `date_creation` datetime NOT NULL,
            PRIMARY KEY (`id`),
            KEY `topic_id` (`topic_id`),
            KEY `user_id` (`user_id`),
            CONSTRAINT `reponses_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE,
            CONSTRAINT `reponses_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    }

    private static function insertDemoData()
    {
        // Insertion des utilisateurs
        $users = [
            // ID 1: Administrateur
            ['Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', null, null, null, null, 'admin.jpg', null],

            // ID 2: Premier étudiant (avec mot de passe 'password')
            ['Dupont', 'etudiant1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', 'Jean', 51, '15 Rue de Paris', '0612345678', 'dupont.jpg', 'Professionnel'],

            // ID 3: Deuxième étudiant
            ['Martin', 'etudiant2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', 'Sophie', 25, '8 Avenue des Lilas', '0698765432', 'martin.jpg', 'Étudiant']
        ];

        $stmt = self::$pdo->prepare("INSERT INTO `users` (nom, email, password, role, prenom, age, adresse, telephone, photo_profil, fonction) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($users as $user) {
            $stmt->execute($user);
        }

        // Insertion des cours
        $cours = [
            ['Introduction à l\'IA', 'Prof. Dupont', 'Bases de l\'intelligence artificielle.', 'Débutant', '5 heures', 'ia.jpg', null, null],
            ['Développement Web', 'Prof. Martin', 'Créer des sites web modernes.', 'Difficile', '3 mois', 'html.jpg', null, null],
            ['Administration Système', 'Prof. Durand', 'Gestion d\'infrastructure.', 'Intermédiaire', '6 mois', 'system.jpg', null, null],
            ['Programmation Python', 'Prof. Petit', 'Apprenez Python de zéro.', 'Débutant', '2 mois', 'python.png', null, null],
            ['Bases de Données', 'Prof. Moreau', 'Modélisation et SQL.', 'Intermédiaire', '4 mois', 'database.jpg', null, null],
            ['Développement Mobile', 'Prof. Lefevre', 'Applications Android & iOS.', 'Difficile', '5 mois', 'mobile.jpg', null, null],

        ];


        $stmt = self::$pdo->prepare("INSERT INTO `cours` (nom, professeur, contenu, niveau, duree, image, pdf, video) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($cours as $c) {
            $stmt->execute($c);
        }

        // Insertion des chapitres (pour le cours 1 : IA)
        $chapitres_cours1 = [
            [1, 'Introduction à l\'IA', 'Ce chapitre présente les concepts fondamentaux de l\'intelligence artificielle et son histoire.', 'introIA.pdf', 'https://youtu.be/yQLmgw3rClM?si=yAFjSel41iFi7pL1'],
            [1, 'Machine Learning', 'Découvrez les principes de l\'apprentissage automatique et ses applications.', 'ML.pdf', 'https://youtu.be/RC7GTAKoFGA'],
            [1, 'Deep Learning', 'Explorez les réseaux de neurones et les architectures avancées.', 'DL.pdf', 'DL.mp4']
        ];

        $stmt = self::$pdo->prepare("INSERT INTO `chapitres` (cours_id, titre, description, pdf, video) VALUES (?, ?, ?, ?, ?)");
        foreach ($chapitres_cours1 as $chap) {
            $stmt->execute($chap);
        }

        // Insertion des chapitres (pour le cours 2 : Web)
        $chapitres_cours2 = [
            [2, 'HTML et CSS', 'Les bases du développement web frontend.', 'HTMLCSS.pdf', null],
            [2, 'JavaScript', 'Apprenez à rendre vos pages interactives.', null, 'https://youtu.be/Ew7KG2j8eII'],
            [2, 'PHP et MySQL', 'Programmation côté serveur et bases de données.', 'PHPSQL.pdf', 'https://youtu.be/-NTvIHHDmg8']
        ];

        foreach ($chapitres_cours2 as $chap) {
            $stmt->execute($chap);
        }

        // Insertion des chapitres (pour le cours 3 : Administration Système)
        $chapitres_cours3 = [
            [3, 'Introduction aux systèmes d\'exploitation', 'Découverte des concepts fondamentaux des OS.', null, 'https://youtu.be/AcZ87MTiXr4'],
            [3, 'Administration Linux', 'Gestion des systèmes Linux en environnement professionnel.', 'Linux.pdf', null]
        ];

        foreach ($chapitres_cours3 as $chap) {
            $stmt->execute($chap);
        }

        // Cours 4 : Programmation Python
        $chapitres_cours4 = [
            [4, 'Introduction à Python', 'Syntaxe de base, variables et types.', 'PythonIntro.pdf', null],
            [4, 'Contrôle de flux', 'Conditions, boucles, exceptions.', null, 'https://youtu.be/WGJJIrtnfpk'],
            [4, 'Programmation orientée objet', 'Classes et objets en Python.', 'OOP_Python.pdf', 'https://youtu.be/qiSCMNBIP2g']
        ];
        foreach ($chapitres_cours4 as $chap) {
            $stmt->execute($chap);
        }

        // Cours 5 : Bases de Données
        $chapitres_cours5 = [
            [5, 'Conception de bases de données', 'Modélisation conceptuelle.', 'BDD_Modelisation.pdf', null],
            [5, 'Langage SQL', 'Créer, modifier, interroger une base.', 'SQL.pdf', 'https://youtu.be/7S_tz1z_5bA'],
            [5, 'Sécurité des données', 'Gestion des permissions et sécurité.', null, 'https://youtu.be/CibYKICXU_k']
        ];
        foreach ($chapitres_cours5 as $chap) {
            $stmt->execute($chap);
        }

        // Cours 6 : Développement Mobile
        $chapitres_cours6 = [
            [6, 'Introduction aux apps mobiles', 'Comprendre Android et iOS.', 'MobileIntro.pdf', null],
            [6, 'Développement Android', 'Créer sa première app Android.', null, 'https://youtu.be/fis26HvvDII'],
            [6, 'Développement iOS', 'Première app iOS avec Swift.', 'iOSApp.pdf', 'https://youtu.be/5NV6Rdv1a3I']
        ];
        foreach ($chapitres_cours6 as $chap) {
            $stmt->execute($chap);
        }

        // Marquage des chapitres comme vus pour l'étudiant 1 (Jean Dupont)
        $progression_etudiant1 = [
            [2, 1], // Etudiant 1 (ID: 2) a vu le chapitre 1 (Introduction à l'IA)
            [2, 2], // Etudiant 1 (ID: 2) a vu le chapitre 2 (Machine Learning)
            [2, 3]  // Etudiant 1 (ID: 2) a vu le chapitre 3 (Deep Learning)
        ];

        $stmt = self::$pdo->prepare("INSERT INTO `chapitre_progression` (user_id, chapitre_id) VALUES (?, ?)");
        foreach ($progression_etudiant1 as $prog) {
            $stmt->execute($prog);
        }

        // Inscription des étudiants aux cours
        $inscriptions = [
            [2, 1], // Etudiant 1 (Jean Dupont, ID: 2) s'inscrit au cours 1 (IA)
            [2, 2], // Etudiant 1 (Jean Dupont, ID: 2) s'inscrit au cours 2 (Web)
            [2, 3], // Etudiant 1 (Jean Dupont, ID: 2) s'inscrit au cours 3 (Admin Système)
            [3, 1], // Etudiant 2 (Sophie Martin, ID: 3) s'inscrit au cours 1 (IA)
            [3, 3]  // Etudiant 2 (Sophie Martin, ID: 3) s'inscrit au cours 3 (Admin Système)
        ];

        $stmt = self::$pdo->prepare("INSERT INTO `cours_inscriptions` (user_id, cours_id) VALUES (?, ?)");
        foreach ($inscriptions as $insc) {
            try {
                $stmt->execute($insc);
            } catch (PDOException $e) {
                // Ignorer les erreurs de clé dupliquée
                if (!strpos($e->getMessage(), 'Duplicate entry')) {
                    throw $e;
                }
            }
        }

        // Ajouter un quiz pour le cours 1 (IA)
        self::insertDemoQuizzes();

        // Ajouter des topics et réponses dans le forum
        self::insertDemoForumData();

        // Ajouter des feedbacks généraux
        $feedbacks = [
            [2, 5, 'Très bonne plateforme de formation en ligne. Interface intuitive et contenus de qualité.'],
            [3, 4, 'Bon site, mais quelques bugs mineurs. Les cours sont très intéressants.'],
            [4, 4, 'Bonne diversité de cours, mais quelques améliorations pourraient être apportées sur la partie quiz.'],
            [3, 4, 'Excellente expérience d\'apprentissage en ligne, continuez comme ça !'],
            [4, 3, 'Les vidéos sont bien faites, mais quelques fichiers PDF supplémentaires seraient utiles.'],

        ];

        $stmt = self::$pdo->prepare("INSERT INTO `feedbacks_generaux` (etudiant_id, note, commentaire) VALUES (?, ?, ?)");
        foreach ($feedbacks as $feedback) {
            $stmt->execute($feedback);
        }

        // Ajouter des feedbacks pour les cours
        $feedbacks_cours = [
            [1, 2, 5, 'Excellent cours sur l\'IA ! Les explications sont claires et les exemples pertinents.']
            // Le feedback de Jean Dupont (ID: 2) sur le cours de développement web (ID: 2) a été retiré
        ];

        $stmt = self::$pdo->prepare("INSERT INTO `feedbacks_cours` (cours_id, etudiant_id, note, commentaire) VALUES (?, ?, ?, ?)");
        foreach ($feedbacks_cours as $feedback) {
            try {
                $stmt->execute($feedback);
            } catch (PDOException $e) {
                // Ignorer les erreurs de clé dupliquée
                if (!strpos($e->getMessage(), 'Duplicate entry')) {
                    throw $e;
                }
            }
        }
    }

    private static function insertDemoQuizzes()
    {
        // Ajouter un quiz pour le cours d'IA
        $stmt = self::$pdo->prepare("INSERT INTO `quizzes` (cours_id, titre, description) VALUES (?, ?, ?)");
        $stmt->execute([1, 'Quiz d\'introduction à l\'IA', 'Testez vos connaissances sur les bases de l\'intelligence artificielle']);
        $quiz_id = self::$pdo->lastInsertId();

        // Ajouter des questions pour ce quiz
        $stmt = self::$pdo->prepare("INSERT INTO `questions` (quiz_id, texte, type, ordre) VALUES (?, ?, ?, ?)");

        // Question 1 (choix unique)
        $stmt->execute([$quiz_id, 'Qu\'est-ce que le Machine Learning ?', 'unique', 1]);
        $question_id = self::$pdo->lastInsertId();

        // Options pour la question 1
        $stmt = self::$pdo->prepare("INSERT INTO `options` (question_id, texte, est_correcte, ordre) VALUES (?, ?, ?, ?)");
        $options = [
            [$question_id, 'Un domaine de l\'IA qui permet aux machines d\'apprendre à partir des données', 1, 1],
            [$question_id, 'Un langage de programmation spécifique pour l\'IA', 0, 2],
            [$question_id, 'Un type de robot intelligent', 0, 3],
            [$question_id, 'Un algorithme spécifique créé par Google', 0, 4]
        ];
        foreach ($options as $option) {
            $stmt->execute($option);
        }

        // Question 2 (choix multiple)
        $stmt = self::$pdo->prepare("INSERT INTO `questions` (quiz_id, texte, type, ordre) VALUES (?, ?, ?, ?)");
        $stmt->execute([$quiz_id, 'Quels sont les types d\'apprentissage en Machine Learning ?', 'multiple', 2]);
        $question_id = self::$pdo->lastInsertId();

        // Options pour la question 2
        $stmt = self::$pdo->prepare("INSERT INTO `options` (question_id, texte, est_correcte, ordre) VALUES (?, ?, ?, ?)");
        $options = [
            [$question_id, 'Apprentissage supervisé', 1, 1],
            [$question_id, 'Apprentissage non supervisé', 1, 2],
            [$question_id, 'Apprentissage par renforcement', 1, 3],
            [$question_id, 'Apprentissage automatisé', 0, 4]
        ];
        foreach ($options as $option) {
            $stmt->execute($option);
        }

        // Ajouter un quiz pour le cours de développement web
        $stmt = self::$pdo->prepare("INSERT INTO `quizzes` (cours_id, titre, description) VALUES (?, ?, ?)");
        $stmt->execute([2, 'Quiz HTML/CSS', 'Vérifiez vos connaissances sur le développement web frontend']);
        $quiz_id = self::$pdo->lastInsertId();

        // Question 1 (choix unique)
        $stmt = self::$pdo->prepare("INSERT INTO `questions` (quiz_id, texte, type, ordre) VALUES (?, ?, ?, ?)");
        $stmt->execute([$quiz_id, 'Quelle balise HTML est utilisée pour créer un lien hypertexte ?', 'unique', 1]);
        $question_id = self::$pdo->lastInsertId();

        // Options pour la question 1
        $stmt = self::$pdo->prepare("INSERT INTO `options` (question_id, texte, est_correcte, ordre) VALUES (?, ?, ?, ?)");
        $options = [
            [$question_id, '<a>', 1, 1],
            [$question_id, '<link>', 0, 2],
            [$question_id, '<href>', 0, 3],
            [$question_id, '<url>', 0, 4]
        ];
        foreach ($options as $option) {
            $stmt->execute($option);
        }

        // Enregistrer une tentative pour l'étudiant 1 sur le quiz d'IA
        $stmt = self::$pdo->prepare("INSERT INTO `tentatives_quiz` (user_id, quiz_id, score, score_max) VALUES (?, ?, ?, ?)");
        $stmt->execute([2, 1, 2, 2]); // Étudiant 1 a obtenu 2/2 au quiz d'IA
        $tentative_id = self::$pdo->lastInsertId();

        // Enregistrer les réponses de l'étudiant
        $stmt = self::$pdo->prepare("INSERT INTO `reponses_etudiant` (tentative_id, question_id, option_id) VALUES (?, ?, ?)");
        $stmt->execute([$tentative_id, 1, 1]); // Bonne réponse à la question 1
        $stmt->execute([$tentative_id, 2, 5]); // Première bonne réponse à la question 2
        $stmt->execute([$tentative_id, 2, 6]); // Deuxième bonne réponse à la question 2
        $stmt->execute([$tentative_id, 2, 7]); // Troisième bonne réponse à la question 2
        // Ajouter un quiz pour le cours d'Administration Système
        $stmt = self::$pdo->prepare("INSERT INTO `quizzes` (cours_id, titre, description) VALUES (?, ?, ?)");
        $stmt->execute([3, 'Quiz Administration Système', 'Testez vos connaissances sur l\'administration système']);
        $quiz_id = self::$pdo->lastInsertId();

        // Question 1 (choix unique)
        $stmt = self::$pdo->prepare("INSERT INTO `questions` (quiz_id, texte, type, ordre) VALUES (?, ?, ?, ?)");
        $stmt->execute([$quiz_id, 'Quel est le rôle d\'un système d\'exploitation ?', 'unique', 1]);
        $question_id = self::$pdo->lastInsertId();

        // Options
        $stmt = self::$pdo->prepare("INSERT INTO `options` (question_id, texte, est_correcte, ordre) VALUES (?, ?, ?, ?)");
        $options = [
            [$question_id, 'Gérer le matériel et les logiciels', 1, 1],
            [$question_id, 'Compiler le code uniquement', 0, 2],
            [$question_id, 'Servir uniquement d\'interface utilisateur', 0, 3],
            [$question_id, 'Augmenter la vitesse du réseau', 0, 4]
        ];
        foreach ($options as $option) {
            $stmt->execute($option);
        }

        // Ajouter un quiz pour le cours Programmation Python
        $stmt = self::$pdo->prepare("INSERT INTO `quizzes` (cours_id, titre, description) VALUES (?, ?, ?)");
        $stmt->execute([4, 'Quiz Programmation Python', 'Évaluez vos connaissances sur Python']);
        $quiz_id = self::$pdo->lastInsertId();

        // Question 1
        $stmt = self::$pdo->prepare("INSERT INTO `questions` (quiz_id, texte, type, ordre) VALUES (?, ?, ?, ?)");
        $stmt->execute([$quiz_id, 'Quelle syntaxe est correcte pour afficher quelque chose en Python ?', 'unique', 1]);
        $question_id = self::$pdo->lastInsertId();

        $stmt = self::$pdo->prepare("INSERT INTO `options` (question_id, texte, est_correcte, ordre) VALUES (?, ?, ?, ?)");
        $options = [
            [$question_id, 'print("Hello World")', 1, 1],
            [$question_id, 'echo "Hello World"', 0, 2],
            [$question_id, 'printf("Hello World")', 0, 3],
            [$question_id, 'console.log("Hello World")', 0, 4]
        ];
        foreach ($options as $option) {
            $stmt->execute($option);
        }

        // Ajouter un quiz pour le cours Bases de Données
        $stmt = self::$pdo->prepare("INSERT INTO `quizzes` (cours_id, titre, description) VALUES (?, ?, ?)");
        $stmt->execute([5, 'Quiz Bases de Données', 'Testez vos bases en conception de bases de données']);
        $quiz_id = self::$pdo->lastInsertId();

        // Question 1
        $stmt = self::$pdo->prepare("INSERT INTO `questions` (quiz_id, texte, type, ordre) VALUES (?, ?, ?, ?)");
        $stmt->execute([$quiz_id, 'Quel langage est utilisé pour interroger une base de données relationnelle ?', 'unique', 1]);
        $question_id = self::$pdo->lastInsertId();

        $stmt = self::$pdo->prepare("INSERT INTO `options` (question_id, texte, est_correcte, ordre) VALUES (?, ?, ?, ?)");
        $options = [
            [$question_id, 'SQL', 1, 1],
            [$question_id, 'HTML', 0, 2],
            [$question_id, 'CSS', 0, 3],
            [$question_id, 'PHP', 0, 4]
        ];
        foreach ($options as $option) {
            $stmt->execute($option);
        }

        // Ajouter un quiz pour le cours Développement Mobile
        $stmt = self::$pdo->prepare("INSERT INTO `quizzes` (cours_id, titre, description) VALUES (?, ?, ?)");
        $stmt->execute([6, 'Quiz Développement Mobile', 'Vérifiez vos connaissances en développement mobile']);
        $quiz_id = self::$pdo->lastInsertId();

        // Question 1
        $stmt = self::$pdo->prepare("INSERT INTO `questions` (quiz_id, texte, type, ordre) VALUES (?, ?, ?, ?)");
        $stmt->execute([$quiz_id, 'Quel langage est utilisé pour développer des applications Android ?', 'unique', 1]);
        $question_id = self::$pdo->lastInsertId();

        $stmt = self::$pdo->prepare("INSERT INTO `options` (question_id, texte, est_correcte, ordre) VALUES (?, ?, ?, ?)");
        $options = [
            [$question_id, 'Java', 1, 1],
            [$question_id, 'Swift', 0, 2],
            [$question_id, 'Python', 0, 3],
            [$question_id, 'C#', 0, 4]
        ];
        foreach ($options as $option) {
            $stmt->execute($option);
        }
    }

    private static function insertDemoForumData()
    {
        // Créer un topic dans le forum du cours d'IA
        $stmt = self::$pdo->prepare("INSERT INTO `topics` (cours_id, user_id, titre, contenu, date_creation) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([1, 2, 'Problème avec les réseaux de neurones', 'Bonjour, j\'ai du mal à comprendre le concept de rétropropagation dans les réseaux de neurones. Quelqu\'un pourrait-il m\'expliquer simplement ?']);
        $topic_id = self::$pdo->lastInsertId();

        // Ajouter des réponses à ce topic
        $stmt = self::$pdo->prepare("INSERT INTO `reponses` (topic_id, user_id, contenu, date_creation) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$topic_id, 3, 'La rétropropagation est simplement un algorithme qui permet d\'ajuster les poids des connexions entre les neurones en calculant l\'erreur à la sortie puis en la propageant en arrière. Cela permet au réseau "d\'apprendre" de ses erreurs.']);
        $stmt->execute([$topic_id, 2, 'Merci pour l\'explication ! Est-ce que quelqu\'un pourrait recommander des ressources en français pour approfondir ce sujet ?']);

        // Créer un autre topic dans le forum du cours de développement web
        $stmt = self::$pdo->prepare("INSERT INTO `topics` (cours_id, user_id, titre, contenu, date_creation) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([2, 2, 'Différence entre Flexbox et Grid', 'Quelle est la différence entre Flexbox et Grid en CSS ? Dans quels cas devrait-on utiliser l\'un plutôt que l\'autre ?']);
        $topic_id = self::$pdo->lastInsertId();

        // Créer un topic dans le forum du cours d'Administration Système par Sophie Martin
        $stmt = self::$pdo->prepare("INSERT INTO `topics` (cours_id, user_id, titre, contenu, date_creation) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([3, 3, 'Configuration de serveurs virtuels sous Linux', 'Bonjour à tous, je suis en train d\'apprendre à configurer des serveurs virtuels sous Linux. Avez-vous des conseils ou bonnes pratiques à partager ? Merci d\'avance.']);
        $topic_id = self::$pdo->lastInsertId();

        // Ajouter une discussion de 4 échanges entre Sophie Martin et Jean Dupont
        $stmt = self::$pdo->prepare("INSERT INTO `reponses` (topic_id, user_id, contenu, date_creation) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$topic_id, 2, 'Bonjour Sophie, j\'ai récemment configuré plusieurs serveurs virtuels. Je te conseille d\'abord de bien maîtriser les commandes de base comme ls, cd, chmod, etc. Ensuite, apprends à utiliser SSH correctement pour te connecter à distance.']);

        $stmt->execute([$topic_id, 3, 'Merci Jean pour ces conseils ! J\'ai déjà une bonne maîtrise des commandes de base. As-tu des ressources à me recommander pour la sécurisation d\'un serveur ?']);

        $stmt->execute([$topic_id, 2, 'Bien sûr ! Je te recommande de configurer un pare-feu avec UFW ou iptables, de désactiver la connexion root en SSH, d\'utiliser des clés SSH plutôt que des mots de passe et de mettre en place fail2ban pour bloquer les tentatives d\'intrusion.']);

        $stmt->execute([$topic_id, 3, 'Super, merci pour ces infos détaillées. J\'ai commencé à mettre en place UFW et c\'est effectivement très utile. As-tu des conseils sur la configuration de NGINX comme reverse proxy ?']);

        $stmt->execute([$topic_id, 2, 'NGINX est excellent comme reverse proxy ! N\'oublie pas d\'activer HTTPS avec Let\'s Encrypt, c\'est gratuit et très simple à mettre en place avec certbot.']);
    }
}
