<?php
class Database {
    private static $pdo;
    
    public static function connect() {
        if (!self::$pdo) {
            try {
                // Vérification si la base de données existe déjà
                self::initDatabase();
                
                // Connexion à la base de données
                self::$pdo = new PDO('mysql:host=localhost;dbname=elearning', 'root', '');
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur DB: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
    
    private static function initDatabase() {
        try {
            // Connexion à MySQL sans spécifier de base de données
            $tempPdo = new PDO('mysql:host=localhost', 'root', '');
            $tempPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Vérifier si la base de données existe
            $stmt = $tempPdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'elearning'");
            $dbExists = $stmt->fetch();
            
            if (!$dbExists) {
                // Créer la base de données si elle n'existe pas
                $sqlFile = file_get_contents('../app/config/elearning.sql');
                
                // Exécuter les instructions SQL une par une
                $tempPdo->exec("CREATE DATABASE IF NOT EXISTS elearning");
                $tempPdo->exec("USE elearning");
                
                // Séparer les instructions SQL individuelles
                $queries = self::splitSqlQueries($sqlFile);
                
                foreach ($queries as $query) {
                    if (trim($query) !== '') {
                        $tempPdo->exec($query);
                    }
                }
                
                // Log de réussite
                $logFile = fopen("../logs/db_init.log", "a");
                fwrite($logFile, date('Y-m-d H:i:s') . " - Base de données initialisée avec succès\n");
                fclose($logFile);
            }
            
            // Fermer la connexion temporaire
            $tempPdo = null;
            
        } catch (PDOException $e) {
            $errorMessage = "Erreur d'initialisation de la base de données: " . $e->getMessage();
            
            // Log d'erreur
            if (!is_dir("../logs")) {
                mkdir("../logs", 0777, true);
            }
            
            $logFile = fopen("../logs/db_error.log", "a");
            fwrite($logFile, date('Y-m-d H:i:s') . " - " . $errorMessage . "\n");
            fclose($logFile);
            
            die($errorMessage);
        }
    }
    
    private static function splitSqlQueries($sql) {
        // Suppression des commentaires
        $sql = preg_replace('/--(.*?)\\n/', '', $sql);
        
        // Diviser le script en requêtes individuelles (séparées par des points-virgules)
        $queries = preg_split('/;\s*\\n/', $sql);
        
        return $queries;
    }
}