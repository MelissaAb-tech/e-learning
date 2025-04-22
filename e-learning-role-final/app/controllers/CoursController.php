<?php
class CoursController extends Controller
{
    public function voir($id)
    {
        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($id);

        if (!$cours) {
            echo "Cours introuvable.";
            exit;
        }

        // Récupérer les chapitres du cours
        $chapitres = $this->model('Chapitre')->getByCoursId($id);

        // Récupérer les chapitres vus par l'utilisateur
        $chapitres_vus = [];
        if (isset($_SESSION['user'])) {
            $chapitres_vus = $this->model('Chapitre')->getVusParUser($_SESSION['user']['id']);
        }

        // Calculer la progression
        $chapitres_total = count($chapitres);
        $chapitres_termine = 0;
        foreach ($chapitres as $chap) {
            if (in_array($chap['id'], $chapitres_vus)) {
                $chapitres_termine++;
            }
        }
        $progression = $chapitres_total > 0 ? round(($chapitres_termine / $chapitres_total) * 100) : 0;

        // Vérifier le rôle de l'utilisateur
        $user = $_SESSION['user'] ?? null;
        $isAdmin = $user && isset($user['role']) && $user['role'] === 'admin';

        // Afficher la vue en fonction du rôle (admin ou étudiant)
        if ($isAdmin) {
            $this->view('cours/voir_admin', [
                'cours' => $cours,
                'chapitres' => $chapitres,
                'stats' => $this->model('Chapitre')->progressionParCours($id)
            ]);
        } else {
            // Récupérer TOUS les quiz pour ce cours - s'assurer qu'il n'y a pas de filtre supplémentaire
            $quizModel = $this->model('Quiz');
            $quizzes = $quizModel->getByCoursId($id);
            
            // Déboguer le nombre de quiz trouvés
            // error_log("Nombre de quiz trouvés pour le cours $id: " . count($quizzes));
            
            // Si l'utilisateur est connecté, récupérer ses tentatives pour chaque quiz
            if (isset($_SESSION['user'])) {
                $tentativeModel = $this->model('QuizTentative');
                $user_id = $_SESSION['user']['id'];
                
                foreach ($quizzes as &$quiz) {
                    $meilleureTentative = $tentativeModel->getMeilleureTentative($user_id, $quiz['id']);
                    $quiz['meilleure_tentative'] = $meilleureTentative;
                }
            }
            
            $this->view('cours/voir_etudiant', [
                'cours' => $cours,
                'chapitres' => $chapitres,
                'chapitres_vus' => $chapitres_vus,
                'chapitres_termine' => $chapitres_termine,
                'chapitres_total' => $chapitres_total,
                'progression' => $progression,
                'quizzes' => $quizzes
            ]);
        }
    }
}