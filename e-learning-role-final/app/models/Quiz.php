<?php
class Quiz
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($cours_id, $titre, $description)
    {
        $stmt = $this->db->prepare("INSERT INTO quizzes (cours_id, titre, description) VALUES (?, ?, ?)");
        return $stmt->execute([$cours_id, $titre, $description]);
    }

    public function getAll()
    {
        return $this->db->query("SELECT * FROM quizzes ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM quizzes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCoursId($cours_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM quizzes WHERE cours_id = ? ORDER BY id DESC");
        $stmt->execute([$cours_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $titre, $description)
    {
        $stmt = $this->db->prepare("UPDATE quizzes SET titre = ?, description = ? WHERE id = ?");
        return $stmt->execute([$titre, $description, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM quizzes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}