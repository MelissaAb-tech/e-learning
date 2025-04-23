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
}
