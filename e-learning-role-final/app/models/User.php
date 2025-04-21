<?php
class User
{
    private $db;
    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nom, $email, $password)
    {
        $stmt = $this->db->prepare("INSERT INTO users (nom, email, password, role) VALUES (?, ?, ?, 'etudiant')");
        return $stmt->execute([$nom, $email, $password]);
    }
    public function getAllEtudiants()
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role = 'etudiant'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEtudiant($nom, $email, $password)
    {
        $stmt = $this->db->prepare("INSERT INTO users (nom, email, password, role) VALUES (?, ?, ?, 'etudiant')");
        return $stmt->execute([$nom, $email, $password]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Retourner les données sous forme de tableau associatif
    }

    // Méthode pour mettre à jour les informations de l'utilisateur
    public function updateInfo($id, $nom, $prenom, $age, $fonction, $email, $adresse, $telephone, $photo_profil)
    {
        $stmt = $this->db->prepare("UPDATE users SET nom = ?, prenom = ?, age = ?, fonction = ?, email = ?, adresse = ?, telephone = ?, photo_profil = ? WHERE id = ?");
        return $stmt->execute([$nom, $prenom, $age, $fonction, $email, $adresse, $telephone, $photo_profil, $id]);
    }
}
