<?php
class Database {
    private static $pdo;

    public static function connect() {
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
                
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
    
    private static function initDatabase() {
        try {
            // Vérifier si les tables principales existent déjà
            $tables = self::$pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            
            // Si les tables n'existent pas, créer la structure de base
            if (!in_array('users', $tables)) {
                self::createTables();
                self::insertDemoData();
            }
        } catch (PDOException $e) {
            die("Erreur lors de l'initialisation de la base de données: " . $e->getMessage());
        }
    }
    
    private static function createTables() {
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
    }
    
    private static function insertDemoData() {
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
    }
}