<?php
class ForumController extends Controller
{
    public function index($cours_id)
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        // Récupérer les informations du cours
        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($cours_id);

        if (!$cours) {
            echo "Cours introuvable.";
            exit;
        }

        // Récupérer les topics de ce cours
        $topicModel = $this->model('Topic');
        $topics = $topicModel->getByCoursId($cours_id);

        // Afficher la vue du forum
        $this->view('forum/index', [
            'cours' => $cours,
            'topics' => $topics
        ]);
    }

    public function creer($cours_id)
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        // Récupérer les informations du cours
        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($cours_id);

        if (!$cours) {
            echo "Cours introuvable.";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'];
            $contenu = $_POST['contenu'];
            $user_id = $_SESSION['user']['id'];

            $topicModel = $this->model('Topic');
            if ($topicModel->create($cours_id, $user_id, $titre, $contenu)) {
                header("Location: /e-learning-role-final/public/forum/cours/{$cours_id}");
                exit;
            } else {
                $error = "Une erreur est survenue lors de la création du topic.";
                $this->view('forum/creer', [
                    'cours' => $cours,
                    'error' => $error
                ]);
            }
        } else {
            $this->view('forum/creer', [
                'cours' => $cours
            ]);
        }
    }

    public function voir($topic_id)
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        // Récupérer le topic
        $topicModel = $this->model('Topic');
        $topic = $topicModel->getById($topic_id);

        if (!$topic) {
            echo "Topic introuvable.";
            exit;
        }

        // Récupérer le cours associé
        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($topic['cours_id']);

        // Récupérer les réponses
        $reponseModel = $this->model('Reponse');
        $reponses = $reponseModel->getByTopicId($topic_id);

        // Afficher la vue du topic
        $this->view('forum/voir', [
            'cours' => $cours,
            'topic' => $topic,
            'reponses' => $reponses
        ]);
    }

    public function repondre($topic_id)
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /e-learning-role-final/public/forum/voir/{$topic_id}");
            exit;
        }

        $contenu = $_POST['contenu'];
        $user_id = $_SESSION['user']['id'];

        $reponseModel = $this->model('Reponse');
        if ($reponseModel->create($topic_id, $user_id, $contenu)) {
            header("Location: /e-learning-role-final/public/forum/voir/{$topic_id}");
            exit;
        } else {
            // Récupérer le topic pour afficher la page avec une erreur
            $topicModel = $this->model('Topic');
            $topic = $topicModel->getById($topic_id);
            $coursModel = $this->model('Cours');
            $cours = $coursModel->getById($topic['cours_id']);
            $reponses = $reponseModel->getByTopicId($topic_id);

            $this->view('forum/voir', [
                'cours' => $cours,
                'topic' => $topic,
                'reponses' => $reponses,
                'error' => "Une erreur est survenue lors de l'envoi de votre réponse."
            ]);
        }
    }

    public function supprimer_topic($topic_id)
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $isAdmin = $_SESSION['user']['role'] === 'admin';

        $topicModel = $this->model('Topic');
        $topic = $topicModel->getById($topic_id);

        if (!$topic) {
            echo "Topic introuvable.";
            exit;
        }

        // Vérifier si l'utilisateur est l'auteur du topic ou un admin
        if ($isAdmin || $topicModel->belongsToUser($topic_id, $user_id)) {
            $topicModel->delete($topic_id);
            header("Location: /e-learning-role-final/public/forum/cours/{$topic['cours_id']}");
            exit;
        } else {
            echo "Vous n'avez pas les droits pour supprimer ce topic.";
            exit;
        }
    }

    public function supprimer_reponse($reponse_id)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $isAdmin = $_SESSION['user']['role'] === 'admin';

        $reponseModel = $this->model('Reponse');
        $reponse = $reponseModel->getById($reponse_id);

        if (!$reponse) {
            echo "Réponse introuvable.";
            exit;
        }

        $topic_id = $reponse['topic_id'];

        // Vérifier si l'utilisateur est l'auteur de la réponse ou un admin
        if ($isAdmin || $reponseModel->belongsToUser($reponse_id, $user_id)) {
            $reponseModel->delete($reponse_id);
            header("Location: /e-learning-role-final/public/forum/voir/{$topic_id}");
            exit;
        } else {
            echo "Vous n'avez pas les droits pour supprimer cette réponse.";
            exit;
        }
    }
}
