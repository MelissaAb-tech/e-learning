<?php
class User {
    private $db;
    public function __construct() {
        $this->db = Database::connect();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nom, $email, $password) {
        $stmt = $this->db->prepare("INSERT INTO users (nom, email, password, role) VALUES (?, ?, ?, 'etudiant')");
        return $stmt->execute([$nom, $email, $password]);
    }
}
