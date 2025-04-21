<?php
class Chapitre
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }


    public function create($cours_id, $titre, $description, $pdf, $video)
    {
        $stmt = $this->db->prepare("INSERT INTO chapitres (cours_id, titre, description, pdf, video) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$cours_id, $titre, $description, $pdf, $video]);
    }
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM chapitres WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function marquerVu($user_id, $chapitre_id)
    {
        $stmt = $this->db->prepare("INSERT IGNORE INTO chapitre_progression (user_id, chapitre_id) VALUES (?, ?)");
        return $stmt->execute([$user_id, $chapitre_id]);
    }
    public function getVusParUser($user_id)
    {
        $stmt = $this->db->prepare("SELECT chapitre_id FROM chapitre_progression WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'chapitre_id');
    }
    public function progressionParCours($cours_id)
    {
        // Nombre total de chapitres pour ce cours
        $stmt1 = $this->db->prepare("SELECT COUNT(*) as total FROM chapitres WHERE cours_id = ?");
        $stmt1->execute([$cours_id]);
        $totalChapitres = $stmt1->fetchColumn();

        if ($totalChapitres == 0) return [];

        // Nombre de chapitres vus par chaque utilisateur
        $stmt2 = $this->db->prepare("
            SELECT u.id as user_id, u.nom, u.email, COUNT(cp.id) as vus
            FROM users u
            LEFT JOIN chapitre_progression cp ON cp.user_id = u.id
            LEFT JOIN chapitres c ON c.id = cp.chapitre_id
            WHERE c.cours_id = ?
            AND u.role = 'etudiant'
            GROUP BY u.id
        ");
        $stmt2->execute([$cours_id]);
        $result = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        // Calcul progression
        foreach ($result as &$row) {
            $row['progression'] = round(($row['vus'] / $totalChapitres) * 100);
            $row['termine'] = $row['progression'] >= 100;
        }

        return $result;
    }
    public function getTotalChapitres($cours_id)
    {
        $sql = "SELECT COUNT(*) FROM chapitres WHERE cours_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cours_id]);
        return $stmt->fetchColumn();
    }

    public function getChapitresVusParEtudiant($etudiant_id, $cours_id)
    {
        $sql = "SELECT COUNT(*) FROM chapitres_vus WHERE etudiant_id = ? AND cours_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$etudiant_id, $cours_id]);
        return $stmt->fetchColumn();
    }
    public function getByCoursId($cours_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM chapitres WHERE cours_id = ?");
        $stmt->execute([$cours_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Retourner les chapitres pour ce cours
    }
}
