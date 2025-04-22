<?php
class Topic
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($cours_id, $user_id, $titre, $contenu)
    {
        $stmt = $this->db->prepare("INSERT INTO topics (cours_id, user_id, titre, contenu, date_creation) VALUES (?, ?, ?, ?, NOW())");
        return $stmt->execute([$cours_id, $user_id, $titre, $contenu]);
    }

    public function getByCoursId($cours_id)
    {
        $stmt = $this->db->prepare("
            SELECT t.*, u.nom as auteur_nom, COUNT(r.id) as nb_reponses 
            FROM topics t
            LEFT JOIN users u ON t.user_id = u.id
            LEFT JOIN reponses r ON r.topic_id = t.id
            WHERE t.cours_id = ?
            GROUP BY t.id
            ORDER BY t.date_creation DESC
        ");
        $stmt->execute([$cours_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT t.*, u.nom as auteur_nom
            FROM topics t
            LEFT JOIN users u ON t.user_id = u.id
            WHERE t.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $titre, $contenu)
    {
        $stmt = $this->db->prepare("UPDATE topics SET titre = ?, contenu = ? WHERE id = ?");
        return $stmt->execute([$titre, $contenu, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM topics WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function belongsToUser($id, $user_id)
    {
        $stmt = $this->db->prepare("SELECT id FROM topics WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
        return $stmt->rowCount() > 0;
    }
}