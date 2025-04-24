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

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO users (prenom, nom, age, fonction, adresse, telephone, email, password, photo_profil, role)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'etudiant')");
        return $stmt->execute([
            $data['prenom'],
            $data['nom'],
            $data['age'],
            $data['fonction'],
            $data['adresse'],
            $data['telephone'],
            $data['email'],
            $data['password'],
            $data['photo_profil']
        ]);
    }

    public function getAllEtudiants()
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role = 'etudiant'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEtudiant($prenom, $nom, $email, $password, $age, $adresse, $fonction, $telephone, $photo_profil)
    {
        // Vérifier si l'email existe 
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existingEmail = $stmt->fetchColumn();

        if ($existingEmail > 0) {
            // si l'email est déjà pris
            return false; // retourner false si l'email existe 
        }

        // insérer l'étudiant dans la bd
        $stmt = $this->db->prepare("INSERT INTO users (prenom, nom, email, password, age, adresse, fonction, telephone, photo_profil, role) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'etudiant')");
        return $stmt->execute([$prenom, $nom, $email, $password, $age, $adresse, $fonction, $telephone, $photo_profil]);
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
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Retourner les données sous forme de tableau 
    }

    // Méthode pour mettre à jour les informations de l'utilisateur

    public function updateInfo($id, $nom, $prenom, $age, $fonction, $email, $adresse, $telephone, $photo_profil, $password)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET nom = ?, prenom = ?, age = ?, fonction = ?, email = ?, adresse = ?, telephone = ?, photo_profil = ?, password = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$nom, $prenom, $age, $fonction, $email, $adresse, $telephone, $photo_profil, $password, $id]);
    }
}
