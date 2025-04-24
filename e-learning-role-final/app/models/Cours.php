<?php
class Cours
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll()
    {
        return $this->db->query("SELECT * FROM cours")->fetchAll(PDO::FETCH_ASSOC);
    }


    public function create($nom, $professeur, $contenu, $niveau, $duree, $image)
    {
        $stmt = $this->db->prepare("INSERT INTO cours (nom, professeur, contenu, niveau, duree, image) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$nom, $professeur, $contenu, $niveau, $duree, $image]);
    }

    public function update($id, $nom, $professeur, $contenu, $niveau, $duree, $image)
    {
        $stmt = $this->db->prepare("UPDATE cours SET nom=?, professeur=?, contenu=?, niveau=?, duree=?, image=? WHERE id=?");
        return $stmt->execute([$nom, $professeur, $contenu, $niveau, $duree, $image, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM cours WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM cours WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateFichiers($id, $pdf, $video)
    {
        $stmt = $this->db->prepare("UPDATE cours SET pdf = ?, video = ? WHERE id = ?");
        return $stmt->execute([$pdf, $video, $id]);
    }
    public function rechercherCoursPourEtudiant($motCle)
    {
        $motCle = '%' . $motCle . '%';
        $stmt = $this->db->prepare("SELECT * FROM cours WHERE nom LIKE ? OR contenu LIKE ? OR professeur LIKE ?");
        $stmt->execute([$motCle, $motCle, $motCle]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
