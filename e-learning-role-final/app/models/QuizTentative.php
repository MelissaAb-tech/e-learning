<?php
class QuizTentative
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
        // Créer la table si elle n'existe pas
        $this->creerTableSiNecessaire();
    }

    private function creerTableSiNecessaire()
    {
        // Vérifier si la table tentatives_quiz existe
        $result = $this->db->query("SHOW TABLES LIKE 'tentatives_quiz'");
        if ($result->rowCount() === 0) {
            // Créer la table tentatives_quiz
            $this->db->exec("CREATE TABLE `tentatives_quiz` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `quiz_id` int(11) NOT NULL,
                `score` int(11) NOT NULL,
                `score_max` int(11) NOT NULL,
                `date_tentative` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`),
                KEY `quiz_id` (`quiz_id`),
                CONSTRAINT `tentatives_quiz_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
                CONSTRAINT `tentatives_quiz_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

            // Créer la table reponses_etudiant
            $this->db->exec("CREATE TABLE `reponses_etudiant` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `tentative_id` int(11) NOT NULL,
                `question_id` int(11) NOT NULL,
                `option_id` int(11) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `tentative_id` (`tentative_id`),
                KEY `question_id` (`question_id`),
                KEY `option_id` (`option_id`),
                CONSTRAINT `reponses_etudiant_ibfk_1` FOREIGN KEY (`tentative_id`) REFERENCES `tentatives_quiz` (`id`) ON DELETE CASCADE,
                CONSTRAINT `reponses_etudiant_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
                CONSTRAINT `reponses_etudiant_ibfk_3` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        }
    }

    public function creerTentative($user_id, $quiz_id, $score, $score_max)
    {
        $stmt = $this->db->prepare("INSERT INTO tentatives_quiz (user_id, quiz_id, score, score_max) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $quiz_id, $score, $score_max]);
        return $this->db->lastInsertId();
    }

    public function enregistrerReponse($tentative_id, $question_id, $option_id)
    {
        $stmt = $this->db->prepare("INSERT INTO reponses_etudiant (tentative_id, question_id, option_id) VALUES (?, ?, ?)");
        return $stmt->execute([$tentative_id, $question_id, $option_id]);
    }

    public function getTentativeById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tentatives_quiz WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTentativesByUserId($user_id)
    {
        $stmt = $this->db->prepare("SELECT t.*, q.titre as quiz_titre, c.id as cours_id, c.nom as cours_nom 
                                    FROM tentatives_quiz t 
                                    JOIN quizzes q ON t.quiz_id = q.id 
                                    JOIN cours c ON q.cours_id = c.id 
                                    WHERE t.user_id = ? 
                                    ORDER BY t.date_tentative DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTentativesByQuizId($quiz_id)
    {
        $stmt = $this->db->prepare("SELECT t.*, u.nom as user_nom 
                                    FROM tentatives_quiz t 
                                    JOIN users u ON t.user_id = u.id 
                                    WHERE t.quiz_id = ? 
                                    ORDER BY t.date_tentative DESC");
        $stmt->execute([$quiz_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReponsesParTentative($tentative_id)
    {
        $stmt = $this->db->prepare("SELECT re.*, q.texte as question_texte, q.type as question_type, o.texte as option_texte, o.est_correcte 
                                   FROM reponses_etudiant re
                                   JOIN questions q ON re.question_id = q.id
                                   JOIN options o ON re.option_id = o.id
                                   WHERE re.tentative_id = ?");
        $stmt->execute([$tentative_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserReponsesByQuestionId($tentative_id, $question_id)
    {
        $stmt = $this->db->prepare("SELECT option_id FROM reponses_etudiant WHERE tentative_id = ? AND question_id = ?");
        $stmt->execute([$tentative_id, $question_id]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'option_id');
    }

    public function getDerniereTentative($user_id, $quiz_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tentatives_quiz WHERE user_id = ? AND quiz_id = ? ORDER BY date_tentative DESC LIMIT 1");
        $stmt->execute([$user_id, $quiz_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getMeilleureTentative($user_id, $quiz_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tentatives_quiz WHERE user_id = ? AND quiz_id = ? ORDER BY score DESC LIMIT 1");
        $stmt->execute([$user_id, $quiz_id]);
        $tentative = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Si aucune tentative n'est trouvée, retourner null au lieu d'un tableau vide
        return $tentative ? $tentative : null;
    }
}