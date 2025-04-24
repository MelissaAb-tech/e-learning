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
            // Vérifier si les tables principales existent déjà
            $tables = self::$pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

            // Si les tables n'existent pas, créer la structure de base
            if (!in_array('users', $tables)) {
                self::createTables();
                self::insertDemoData();
            }

            // Vérifier si les tables de quiz existent
            if (!in_array('quizzes', $tables)) {
                self::createQuizTables();
            }

            // Vérifier si les tables de forum existent
            if (!in_array('topics', $tables)) {
                self::createForumTables();
            }
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

        // Création des tables de quiz
        self::createQuizTables();

        self::$pdo->exec("CREATE TABLE IF NOT EXISTS `feedbacks_generaux` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `etudiant_id` INT NOT NULL,
                `note` INT NOT NULL CHECK (note BETWEEN 1 AND 5),
                `commentaire` TEXT NOT NULL,
                `date_feedback` DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`etudiant_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
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
            [3, 'lilo', 'lilo@gmail.com', '$2y$10$YxK4mXLf44uASA6a04fR6uemlintf5Nth/b3igynRp.VfCanNWRnS', 'etudiant', 'lilo', 26, '13 RUE g', '0663669', '1745240459_images.png', 'Stagiaire'],
            [8, 'Admin', 'admin@example.com', '$2y$10$YxK4mXLf44uASA6a04fR6uemlintf5Nth/b3igynRp.VfCanNWRnS', 'admin', NULL, NULL, NULL, NULL, NULL, NULL],
            [9, 'ab', 'melissaabider@gmail.com', '$2y$10$1h7b1hquwNttCw5Odi8YZuvbIaPNteBJGRab6cN8ReQwy7gsmtnUC', 'etudiant', 'Melissa', 22, '13 RUE EDGAR FAURE', '0663669774', NULL, 'Stagiaire']
        ];

        $stmt = self::$pdo->prepare("INSERT INTO `users` (`id`, `nom`, `email`, `password`, `role`, `prenom`, `age`, `adresse`, `telephone`, `photo_profil`, `fonction`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($users as $user) {
            $stmt->execute($user);
        }

        // Insertion des cours
        $cours = [
            [1, 'Introduction à l\'IA', 'Prof. Dupont', 'Ce cours présente les bases de l\'intelligence', 'Débutant', '5 heures', 'ia.jpg', 'Billet_aller.pdf', '89ac89e8-b983-4285-a11a-249c8d100ad4-mp4_720p.mp4'],
            [2, 'Développement Web', 'Prof. Martin', 'Apprenez à créer des sites web avec HTML, CSS, JS et PHP.', 'Difficile', '3 mois', 'html.jpg', 'Billet_retour.pdf', '89ac89e8-b983-4285-a11a-249c8d100ad4-mp4_720p.mp4'],
            [7, 'system', 'Prof. Dupont', 'system', 'Facile', '6 mois', 'images.png', NULL, NULL]
        ];

        $stmt = self::$pdo->prepare("INSERT INTO `cours` (`id`, `nom`, `professeur`, `contenu`, `niveau`, `duree`, `image`, `pdf`, `video`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($cours as $c) {
            $stmt->execute($c);
        }

        // Insertion des chapitres
        $chapitres = [
            [1, 1, 'CM_1', 'htdtf', 'projet_LFC_etape5.pdf', '89ac89e8-b983-4285-a11a-249c8d100ad4-mp4_720p.mp4'],
            [4, 1, 'CM_2', 'scszfczfd', 'Billet_aller.pdf', '89ac89e8-b983-4285-a11a-249c8d100ad4-mp4_720p.mp4'],
            [6, 1, 'CM_3', 'frthdrh', 'Billet_retour.pdf', '89ac89e8-b983-4285-a11a-249c8d100ad4-mp4_720p.mp4'],
            [8, 2, 'CM_1', 'cm1', NULL, NULL],
            [9, 2, 'CM_2', 'cm2', NULL, NULL],
            [11, 7, 'CM_1', 'cm1', NULL, NULL],
            [12, 7, 'CM_2', 'cm2', NULL, NULL]
        ];

        $stmt = self::$pdo->prepare("INSERT INTO `chapitres` (`id`, `cours_id`, `titre`, `description`, `pdf`, `video`) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($chapitres as $chap) {
            $stmt->execute($chap);
        }

        // Insertion des données de progression
        $progression = [
            [1, 3, 1],
            [2, 3, 4],
            [4, 3, 6],
            [9, 3, 8],
            [10, 3, 9],
            [8, 3, 10],
            [18, 3, 11],
            [6, 9, 1],
            [7, 9, 4],
            [11, 9, 6],
            [12, 9, 8],
            [15, 9, 9],
            [14, 9, 10],
            [16, 9, 11],
            [17, 9, 12]
        ];

        $stmt = self::$pdo->prepare("INSERT INTO `chapitre_progression` (`id`, `user_id`, `chapitre_id`) VALUES (?, ?, ?)");
        foreach ($progression as $prog) {
            $stmt->execute($prog);
        }

        // Ajouter des quiz de démonstration
        self::insertDemoQuizzes();
        self::$pdo->exec("INSERT INTO `feedbacks_generaux` (`etudiant_id`, `note`, `commentaire`) VALUES (3, 5, 'Très bonne plateforme, simple à utiliser !')");
    }

    private static function insertDemoQuizzes()
    {
        // Ajouter un quiz de demo pour le cours d'IA
        $stmt = self::$pdo->prepare("INSERT INTO `quizzes` (`cours_id`, `titre`, `description`) VALUES (?, ?, ?)");
        $stmt->execute([1, 'Quiz d\'introduction à l\'IA', 'Testez vos connaissances sur les bases de l\'intelligence artificielle']);
        $quiz_id = self::$pdo->lastInsertId();

        // Ajouter des questions pour ce quiz
        $stmt = self::$pdo->prepare("INSERT INTO `questions` (`quiz_id`, `texte`, `type`, `ordre`) VALUES (?, ?, ?, ?)");

        // Question 1 (choix unique)
        $stmt->execute([$quiz_id, 'Qu\'est-ce que le Machine Learning ?', 'unique', 1]);
        $question_id = self::$pdo->lastInsertId();

        // Options pour la question 1
        $stmt = self::$pdo->prepare("INSERT INTO `options` (`question_id`, `texte`, `est_correcte`, `ordre`) VALUES (?, ?, ?, ?)");
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
        $stmt = self::$pdo->prepare("INSERT INTO `questions` (`quiz_id`, `texte`, `type`, `ordre`) VALUES (?, ?, ?, ?)");
        $stmt->execute([$quiz_id, 'Quels sont les types d\'apprentissage en Machine Learning ?', 'multiple', 2]);
        $question_id = self::$pdo->lastInsertId();

        // Options pour la question 2
        $stmt = self::$pdo->prepare("INSERT INTO `options` (`question_id`, `texte`, `est_correcte`, `ordre`) VALUES (?, ?, ?, ?)");
        $options = [
            [$question_id, 'Apprentissage supervisé', 1, 1],
            [$question_id, 'Apprentissage non supervisé', 1, 2],
            [$question_id, 'Apprentissage par renforcement', 1, 3],
            [$question_id, 'Apprentissage automatisé', 0, 4]
        ];
        foreach ($options as $option) {
            $stmt->execute($option);
        }
    }
}