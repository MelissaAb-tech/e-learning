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

    public function getByCoursId($cours_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM chapitres WHERE cours_id = ?");
        $stmt->execute([$cours_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM chapitres WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
