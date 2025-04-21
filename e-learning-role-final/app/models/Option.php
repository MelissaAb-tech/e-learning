<?php
class Option
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($question_id, $texte, $est_correcte, $ordre)
    {
        $stmt = $this->db->prepare("INSERT INTO options (question_id, texte, est_correcte, ordre) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$question_id, $texte, $est_correcte, $ordre]);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM options WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByQuestionId($question_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM options WHERE question_id = ? ORDER BY ordre ASC");
        $stmt->execute([$question_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $texte, $est_correcte, $ordre)
    {
        $stmt = $this->db->prepare("UPDATE options SET texte = ?, est_correcte = ?, ordre = ? WHERE id = ?");
        return $stmt->execute([$texte, $est_correcte, $ordre, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM options WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteByQuestionId($question_id)
    {
        $stmt = $this->db->prepare("DELETE FROM options WHERE question_id = ?");
        return $stmt->execute([$question_id]);
    }

    public function getHighestOrder($question_id)
    {
        $stmt = $this->db->prepare("SELECT MAX(ordre) as max_ordre FROM options WHERE question_id = ?");
        $stmt->execute([$question_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['max_ordre'] ?? 0;
    }
}