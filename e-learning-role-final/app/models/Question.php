<?php
class Question
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($quiz_id, $texte, $type, $ordre)
    {
        $stmt = $this->db->prepare("INSERT INTO questions (quiz_id, texte, type, ordre) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$quiz_id, $texte, $type, $ordre])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM questions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByQuizId($quiz_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY ordre ASC");
        $stmt->execute([$quiz_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $texte, $type, $ordre)
    {
        $stmt = $this->db->prepare("UPDATE questions SET texte = ?, type = ?, ordre = ? WHERE id = ?");
        return $stmt->execute([$texte, $type, $ordre, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM questions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getHighestOrder($quiz_id)
    {
        $stmt = $this->db->prepare("SELECT MAX(ordre) as max_ordre FROM questions WHERE quiz_id = ?");
        $stmt->execute([$quiz_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['max_ordre'] ?? 0;
    }
}