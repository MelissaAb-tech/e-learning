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

        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($cours_id);

        if (!$cours) {
            echo "Cours introuvable.";
            exit;
        }

        // Cette vue sera à implémenter plus tard
        $this->view('forum/index', [
            'cours' => $cours,
            'message' => 'Le forum pour ce cours sera disponible prochainement.'
        ]);
    }
}