<?php
class CoursFeedback
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
        // Vérifier si la table existe
        $result = $this->db->query("SHOW TABLES LIKE 'feedbacks_cours'");
        if ($result->rowCount() === 0) {
            // Créer la table
            $this->db->exec("CREATE TABLE `feedbacks_cours` (
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
    }

    // Ajouter un feedback pour un cours
    public function create($cours_id, $etudiant_id, $note, $commentaire)
    {
        // Vérifier si l'étudiant a déjà donné un feedback pour ce cours
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM feedbacks_cours WHERE etudiant_id = ? AND cours_id = ?");
        $stmt->execute([$etudiant_id, $cours_id]);
        $exists = $stmt->fetchColumn() > 0;

        if ($exists) {
            // Mettre à jour le feedback existant
            $stmt = $this->db->prepare("UPDATE feedbacks_cours SET note = ?, commentaire = ?, date_feedback = NOW() WHERE etudiant_id = ? AND cours_id = ?");
            return $stmt->execute([$note, $commentaire, $etudiant_id, $cours_id]);
        } else {
            // Créer un nouveau feedback
            $stmt = $this->db->prepare("INSERT INTO feedbacks_cours (cours_id, etudiant_id, note, commentaire) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$cours_id, $etudiant_id, $note, $commentaire]);
        }
    }

    // Récupérer le feedback d'un étudiant pour un cours
    public function getByEtudiantAndCours($etudiant_id, $cours_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM feedbacks_cours WHERE etudiant_id = ? AND cours_id = ?");
        $stmt->execute([$etudiant_id, $cours_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer tous les feedbacks pour un cours
    public function getByCours($cours_id)
    {
        $stmt = $this->db->prepare("
            SELECT fc.*, u.nom, u.prenom, u.email
            FROM feedbacks_cours fc
            JOIN users u ON fc.etudiant_id = u.id
            WHERE fc.cours_id = ?
            ORDER BY fc.date_feedback DESC
        ");
        $stmt->execute([$cours_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Calculer la note moyenne pour un cours
    public function getMoyenneNotesParCours($cours_id)
    {
        $stmt = $this->db->prepare("SELECT AVG(note) as moyenne_notes FROM feedbacks_cours WHERE cours_id = ?");
        $stmt->execute([$cours_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['moyenne_notes'] ?: 0; // s'il n'y a pas de notes
    }

    // Compter le nombre de feedbacks pour un cours
    public function getNombreFeedbacksParCours($cours_id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM feedbacks_cours WHERE cours_id = ?");
        $stmt->execute([$cours_id]);
        return $stmt->fetchColumn();
    }
}
