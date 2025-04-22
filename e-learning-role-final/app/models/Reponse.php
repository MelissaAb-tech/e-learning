<?php
class Reponse
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($topic_id, $user_id, $contenu)
    {
        $stmt = $this->db->prepare("INSERT INTO reponses (topic_id, user_id, contenu, date_creation) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$topic_id, $user_id, $contenu]);
    }

    public function getByTopicId($topic_id)
    {
        $stmt = $this->db->prepare("
            SELECT r.*, u.nom as auteur_nom 
            FROM reponses r
            LEFT JOIN users u ON r.user_id = u.id
            WHERE r.topic_id = ?
            ORDER BY r.date_creation ASC
        ");
        $stmt->execute([$topic_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM reponses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $contenu)
    {
        $stmt = $this->db->prepare("UPDATE reponses SET contenu = ? WHERE id = ?");
        return $stmt->execute([$contenu, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM reponses WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function belongsToUser($id, $user_id)
    {
        $stmt = $this->db->prepare("SELECT id FROM reponses WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
        return $stmt->rowCount() > 0;
    }
}