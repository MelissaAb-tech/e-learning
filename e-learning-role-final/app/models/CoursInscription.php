<?php
class CoursInscription
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
        // Créer la table si elle n'existe pas
        $this->creerTableSiNecessaire();
    }

    private function creerTableSiNecessaire()
    {
        // Vérifier si la table cours_inscriptions existe
        $result = $this->db->query("SHOW TABLES LIKE 'cours_inscriptions'");
        if ($result->rowCount() === 0) {
            // Créer la table
            $this->db->exec("CREATE TABLE `cours_inscriptions` (
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
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
        }
    }

    // Inscrire un étudiant à un cours
    public function inscrire($user_id, $cours_id)
    {
        $stmt = $this->db->prepare("INSERT IGNORE INTO cours_inscriptions (user_id, cours_id) VALUES (?, ?)");
        return $stmt->execute([$user_id, $cours_id]);
    }

    // Désinscrire un étudiant d'un cours
    public function desinscrire($user_id, $cours_id)
    {
        $stmt = $this->db->prepare("DELETE FROM cours_inscriptions WHERE user_id = ? AND cours_id = ?");
        return $stmt->execute([$user_id, $cours_id]);
    }

    // Vérifier si un étudiant est inscrit à un cours
    public function estInscrit($user_id, $cours_id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM cours_inscriptions WHERE user_id = ? AND cours_id = ?");
        $stmt->execute([$user_id, $cours_id]);
        return $stmt->fetchColumn() > 0;
    }

    // Obtenir tous les cours auxquels un étudiant est inscrit
    public function getCoursParEtudiant($user_id)
    {
        $stmt = $this->db->prepare("
            SELECT c.* 
            FROM cours c
            JOIN cours_inscriptions ci ON c.id = ci.cours_id
            WHERE ci.user_id = ?
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtenir tous les étudiants inscrits à un cours
    public function getEtudiantsParCours($cours_id)
    {
        $stmt = $this->db->prepare("
            SELECT u.* 
            FROM users u
            JOIN cours_inscriptions ci ON u.id = ci.user_id
            WHERE ci.cours_id = ? AND u.role = 'etudiant'
        ");
        $stmt->execute([$cours_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compter le nombre d'étudiants inscrits à un cours
    public function compterEtudiantsInscrits($cours_id)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM cours_inscriptions ci
            JOIN users u ON ci.user_id = u.id
            WHERE ci.cours_id = ? AND u.role = 'etudiant'
        ");
        $stmt->execute([$cours_id]);
        return $stmt->fetchColumn();
    }
}