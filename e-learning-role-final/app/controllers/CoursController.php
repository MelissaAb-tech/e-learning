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
        $chapitreModel = $this->model('Chapitre');
        $chapitres = $chapitreModel->getByCoursId($id);

        // Vérifier le rôle de l'utilisateur
        $user = $_SESSION['user'] ?? null;
        $isAdmin = $user && isset($user['role']) && $user['role'] === 'admin';

        // Afficher la vue en fonction du rôle (admin ou étudiant)
        if ($isAdmin) {
            // Récupération directe des statistiques pour l'admin
            // La vue calculera elle-même les statistiques correctes
            $this->view('cours/voir_admin', [
                'cours' => $cours,
                'chapitres' => $chapitres
            ]);
        } else {
            // Vérifier si l'utilisateur est connecté
            if (!isset($_SESSION['user'])) {
                header('Location: /e-learning-role-final/public/login');
                exit;
            }
            
            // Vérifier si l'utilisateur est inscrit au cours
            $inscriptionModel = $this->model('CoursInscription');
            $estInscrit = $inscriptionModel->estInscrit($_SESSION['user']['id'], $id);
            
            // Si l'utilisateur n'est pas inscrit, le rediriger vers le dashboard
            if (!$estInscrit) {
                header('Location: /e-learning-role-final/public/etudiant/dashboard');
                exit;
            }
            
            // Récupérer les chapitres vus par l'utilisateur
            $chapitres_vus = $chapitreModel->getVusParUser($_SESSION['user']['id']);

            // Calculer la progression
            $chapitres_total = count($chapitres);
            $chapitres_termine = 0;
            foreach ($chapitres as $chap) {
                if (in_array($chap['id'], $chapitres_vus)) {
                    $chapitres_termine++;
                }
            }
            $progression = $chapitres_total > 0 ? round(($chapitres_termine / $chapitres_total) * 100) : 0;

            // Récupérer TOUS les quiz pour ce cours
            $quizModel = $this->model('Quiz');
            $quizzes = $quizModel->getByCoursId($id);
            
            // Récupérer les tentatives de l'étudiant pour chaque quiz
            $tentativeModel = $this->model('QuizTentative');
            $user_id = $_SESSION['user']['id'];
            
            foreach ($quizzes as &$quiz) {
                $meilleureTentative = $tentativeModel->getMeilleureTentative($user_id, $quiz['id']);
                $quiz['meilleure_tentative'] = $meilleureTentative;
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
    
    public function reinitialiser($id)
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        
        // Vérifier que le cours existe
        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($id);
        
        if (!$cours) {
            echo "Cours introuvable.";
            exit;
        }
        
        // 1. Supprimer les chapitres marqués comme vus par l'utilisateur
        $chapitreModel = $this->model('Chapitre');
        $chapitreModel->resetProgressionUser($user_id, $id);
        
        // 2. Supprimer les tentatives de quiz de l'utilisateur pour ce cours
        $quizModel = $this->model('Quiz');
        $quizzes = $quizModel->getByCoursId($id);
        
        $tentativeModel = $this->model('QuizTentative');
        foreach ($quizzes as $quiz) {
            $tentativeModel->deleteTentativesByUserAndQuiz($user_id, $quiz['id']);
        }
        
        // Note: l'inscription au cours est maintenue même après réinitialisation
        
        // Rediriger vers la page du cours
        header("Location: /e-learning-role-final/public/cours/voir/$id");
        exit;
    }

    public function feedback($cours_id)
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        // Vérifier que la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /e-learning-role-final/public/cours/voir/$cours_id");
            exit;
        }

        // Récupérer les données du formulaire
        $etudiant_id = $_SESSION['user']['id'];
        $note = $_POST['rating'];
        $commentaire = $_POST['commentaire'];

        // Vérifier que le cours existe
        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($cours_id);
        
        if (!$cours) {
            echo "Cours introuvable.";
            exit;
        }

        // Vérifier que l'étudiant a terminé le cours à 100%
        $chapitreModel = $this->model('Chapitre');
        $chapitres = $chapitreModel->getByCoursId($cours_id);
        $chapitres_vus = $chapitreModel->getVusParUser($etudiant_id);
        
        $chapitres_total = count($chapitres);
        $chapitres_termine = 0;
        foreach ($chapitres as $chap) {
            if (in_array($chap['id'], $chapitres_vus)) {
                $chapitres_termine++;
            }
        }
        $progression_chapitres = $chapitres_total > 0 ? ($chapitres_termine / $chapitres_total) * 100 : 0;
        
        // Vérifier les quiz
        $quizModel = $this->model('Quiz');
        $quizzes = $quizModel->getByCoursId($cours_id);
        $quiz_total = count($quizzes);
        $quiz_parfait = 0;
        
        if ($quiz_total > 0) {
            $tentativeModel = $this->model('QuizTentative');
            foreach ($quizzes as $quiz) {
                $meilleureTentative = $tentativeModel->getMeilleureTentative($etudiant_id, $quiz['id']);
                if ($meilleureTentative && 
                    isset($meilleureTentative['score']) && 
                    isset($meilleureTentative['score_max']) && 
                    $meilleureTentative['score'] == $meilleureTentative['score_max']) {
                    $quiz_parfait++;
                }
            }
        }
        $progression_quiz = $quiz_total > 0 ? ($quiz_parfait / $quiz_total) * 100 : 100;
        
        $cours_complet = ($progression_chapitres == 100) && ($progression_quiz == 100);
        
        if (!$cours_complet) {
            $_SESSION['error_message'] = "Vous devez terminer le cours à 100% pour pouvoir donner votre avis.";
            header("Location: /e-learning-role-final/public/cours/voir/$cours_id");
            exit;
        }

        // Enregistrer le feedback
        $feedbackModel = $this->model('CoursFeedback');
        if ($feedbackModel->create($cours_id, $etudiant_id, $note, $commentaire)) {
            $_SESSION['success_message'] = "Merci ! Votre avis a bien été enregistré.";
        } else {
            $_SESSION['error_message'] = "Une erreur est survenue lors de l'enregistrement de votre avis.";
        }

        // Rediriger vers la page du cours
        header("Location: /e-learning-role-final/public/cours/voir/$cours_id");
        exit;
    }
}