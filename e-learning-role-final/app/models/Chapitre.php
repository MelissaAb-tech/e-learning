<?php
class Chapitre
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }


    public function create($cours_id, $titre, $description, $pdfs = [], $videos = [], $youtube_links = [])
    {
        // Créer le chapitre principal
        $stmt = $this->db->prepare("INSERT INTO chapitres (cours_id, titre, description) VALUES (?, ?, ?)");
        $stmt->execute([$cours_id, $titre, $description]);
        $chapitre_id = $this->db->lastInsertId();
        
        // Ajouter les PDFs
        if (!empty($pdfs)) {
            $pdfStmt = $this->db->prepare("INSERT INTO chapitre_pdfs (chapitre_id, pdf) VALUES (?, ?)");
            foreach ($pdfs as $pdf) {
                $pdfStmt->execute([$chapitre_id, $pdf]);
            }
        }
        
        // Ajouter les vidéos MP4
        if (!empty($videos)) {
            $videoStmt = $this->db->prepare("INSERT INTO chapitre_videos (chapitre_id, video, est_youtube) VALUES (?, ?, 0)");
            foreach ($videos as $video) {
                $videoStmt->execute([$chapitre_id, $video]);
            }
        }
        
        // Ajouter les liens YouTube
        if (!empty($youtube_links)) {
            $youtubeStmt = $this->db->prepare("INSERT INTO chapitre_videos (chapitre_id, video, est_youtube) VALUES (?, ?, 1)");
            foreach ($youtube_links as $link) {
                $youtubeStmt->execute([$chapitre_id, $link]);
            }
        }
        
        return $chapitre_id;
    }
    
    public function delete($id)
    {
        // Les tables associées seront automatiquement nettoyées grâce à ON DELETE CASCADE
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
        $chapitres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Pour chaque chapitre, récupérer ses PDFs et vidéos
        foreach ($chapitres as &$chapitre) {
            $chapitre['pdfs'] = $this->getPdfsByChapitre($chapitre['id']);
            $chapitre['videos'] = $this->getVideosByChapitre($chapitre['id']);
        }
        
        return $chapitres;
    }
    
    public function getPdfsByChapitre($chapitre_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM chapitre_pdfs WHERE chapitre_id = ?");
        $stmt->execute([$chapitre_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getVideosByChapitre($chapitre_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM chapitre_videos WHERE chapitre_id = ?");
        $stmt->execute([$chapitre_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function resetProgressionUser($user_id, $cours_id)
    {
        // Récupérer tous les chapitres du cours
        $chapitres = $this->getByCoursId($cours_id);
        
        if (empty($chapitres)) {
            return true; // Rien à réinitialiser
        }
        
        // Construire la liste des IDs de chapitres
        $chapitre_ids = array_column($chapitres, 'id');
        $placeholders = implode(',', array_fill(0, count($chapitre_ids), '?'));
        
        // Supprimer les entrées dans la table de progression
        $sql = "DELETE FROM chapitre_progression WHERE user_id = ? AND chapitre_id IN ($placeholders)";
        
        // Préparer les paramètres pour la requête
        $params = array_merge([$user_id], $chapitre_ids);
        
        // Exécuter la requête
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    // Ajout de la méthode pour récupérer un chapitre par son ID
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM chapitres WHERE id = ?");
        $stmt->execute([$id]);
        $chapitre = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($chapitre) {
            $chapitre['pdfs'] = $this->getPdfsByChapitre($id);
            $chapitre['videos'] = $this->getVideosByChapitre($id);
        }
        
        return $chapitre;
    }
    
    // Ajout de la méthode pour mettre à jour un chapitre
    public function update($id, $titre, $description, $pdfs = [], $videos = [], $youtube_links = [])
    {
        // Mettre à jour le chapitre principal
        $stmt = $this->db->prepare("UPDATE chapitres SET titre = ?, description = ? WHERE id = ?");
        $success = $stmt->execute([$titre, $description, $id]);
        
        if (!$success) {
            return false;
        }
        
        // Supprimer les PDFs et vidéos existants
        $this->db->prepare("DELETE FROM chapitre_pdfs WHERE chapitre_id = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM chapitre_videos WHERE chapitre_id = ?")->execute([$id]);
        
        // Ajouter les nouveaux PDFs
        if (!empty($pdfs)) {
            $pdfStmt = $this->db->prepare("INSERT INTO chapitre_pdfs (chapitre_id, pdf) VALUES (?, ?)");
            foreach ($pdfs as $pdf) {
                $pdfStmt->execute([$id, $pdf]);
            }
        }
        
        // Ajouter les nouvelles vidéos MP4
        if (!empty($videos)) {
            $videoStmt = $this->db->prepare("INSERT INTO chapitre_videos (chapitre_id, video, est_youtube) VALUES (?, ?, 0)");
            foreach ($videos as $video) {
                $videoStmt->execute([$id, $video]);
            }
        }
        
        // Ajouter les nouveaux liens YouTube
        if (!empty($youtube_links)) {
            $youtubeStmt = $this->db->prepare("INSERT INTO chapitre_videos (chapitre_id, video, est_youtube) VALUES (?, ?, 1)");
            foreach ($youtube_links as $link) {
                $youtubeStmt->execute([$id, $link]);
            }
        }
        
        return true;
    }
}