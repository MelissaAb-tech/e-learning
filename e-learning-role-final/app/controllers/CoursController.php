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

        $chapitres = $this->model('Chapitre')->getByCoursId($id);

        $user = $_SESSION['user'] ?? null;
        $isAdmin = $user && isset($user['role']) && $user['role'] === 'admin';

        // Traitement admin pour mise à jour des fichiers 
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin) {
        }

        // passe les chapitres à la vue
        if ($isAdmin) {
            $this->view('cours/voir_admin', ['cours' => $cours, 'chapitres' => $chapitres]);
        } else {
            $this->view('cours/voir_etudiant', ['cours' => $cours, 'chapitres' => $chapitres]);
        }
    }
}
