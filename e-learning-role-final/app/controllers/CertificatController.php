<?php
class CertificatController extends Controller
{
    public function generer($cours_id)
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        
        // Vérifier que le cours existe
        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($cours_id);
        
        if (!$cours) {
            echo "Cours introuvable.";
            exit;
        }
        
        // Récupérer les données utilisateur
        $userModel = $this->model('User');
        $user = $userModel->getById($user_id);
        
        // Vérifier si l'utilisateur a complété 100% du cours
        $chapitres = $this->model('Chapitre')->getByCoursId($cours_id);
        $chapitres_vus = $this->model('Chapitre')->getVusParUser($user_id);
        
        $chapitres_total = count($chapitres);
        $chapitres_termine = 0;
        foreach ($chapitres as $chap) {
            if (in_array($chap['id'], $chapitres_vus)) {
                $chapitres_termine++;
            }
        }
        $progression_chapitres = $chapitres_total > 0 ? ($chapitres_termine / $chapitres_total) * 100 : 0;
        
        // Vérifier les quiz
        $quizzes = $this->model('Quiz')->getByCoursId($cours_id);
        $quiz_total = count($quizzes);
        $quiz_parfait = 0;
        
        if ($quiz_total > 0) {
            $tentativeModel = $this->model('QuizTentative');
            foreach ($quizzes as $quiz) {
                $meilleureTentative = $tentativeModel->getMeilleureTentative($user_id, $quiz['id']);
                if ($meilleureTentative && 
                    isset($meilleureTentative['score']) && 
                    isset($meilleureTentative['score_max']) && 
                    $meilleureTentative['score'] == $meilleureTentative['score_max']) {
                    $quiz_parfait++;
                }
            }
        }
        $progression_quiz = $quiz_total > 0 ? ($quiz_parfait / $quiz_total) * 100 : 100;
        
        // Vérifier les conditions pour obtenir le certificat
        $certificat_disponible = ($progression_chapitres == 100) && ($progression_quiz == 100);
        
        if (!$certificat_disponible) {
            $this->view('certificat/non_disponible', [
                'cours' => $cours,
                'progression_chapitres' => $progression_chapitres,
                'progression_quiz' => $progression_quiz
            ]);
            return;
        }
        
        // Afficher le certificat
        $this->view('certificat/certificat', [
            'cours' => $cours,
            'user' => $user,
            'date' => date('d/m/Y')
        ]);
    }
}