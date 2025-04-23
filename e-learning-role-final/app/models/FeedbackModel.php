<?php
class FeedbackModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($etudiant_id, $note, $commentaire)
    {
        // Préparer la requête SQL pour insérer le feedback dans la base de données
        $stmt = $this->db->prepare("INSERT INTO feedbacks_generaux (etudiant_id, note, commentaire) VALUES (?, ?, ?)");
        return $stmt->execute([$etudiant_id, $note, $commentaire]);
    }
    
    // Méthode pour calculer la moyenne des notes
    public function getMoyenneNotes()
    {
        $stmt = $this->db->prepare("SELECT AVG(note) as moyenne_notes FROM feedbacks_generaux");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['moyenne_notes'] ?: 0; // Retourne 0 s'il n'y a pas de notes
    }
    
    // Méthode pour compter le nombre total de feedbacks
    public function getNombreFeedbacks()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM feedbacks_generaux");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    // Nouvelle méthode pour récupérer tous les feedbacks avec infos utilisateur
    public function getAllFeedbacks()
    {
        $stmt = $this->db->prepare("
            SELECT f.*, u.nom, u.prenom, u.email, f.date_feedback
            FROM feedbacks_generaux f
            JOIN users u ON f.etudiant_id = u.id
            ORDER BY f.date_feedback DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}