<?php
class ChapitreController extends Controller
{
    public function ajouter($cours_id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'];
            $description = $_POST['description'];

            $pdf = null;
            $video = null;

            if (!empty($_FILES['pdf']['name'])) {
                $pdf = basename($_FILES['pdf']['name']);
                move_uploaded_file($_FILES['pdf']['tmp_name'], "../public/pdfs/" . $pdf);
            }

            if (!empty($_FILES['video']['name'])) {
                $video = basename($_FILES['video']['name']);
                move_uploaded_file($_FILES['video']['tmp_name'], "../public/videos/" . $video);
            }

            $this->model('Chapitre')->create($cours_id, $titre, $description, $pdf, $video);
            header("Location: /e-learning-role-final/public/cours/voir/$cours_id");
            exit;
        }

        $this->view('admin/ajouter_chapitre', ['cours_id' => $cours_id]);
    }
    public function supprimer($id, $cours_id)
    {
        $this->model('Chapitre')->delete($id);
        header("Location: /e-learning-role-final/public/cours/voir/$cours_id");
        exit;
    }
    public function valider()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $chapitre_id = $_POST['chapitre_id'];

        $this->model('Chapitre')->marquerVu($user_id, $chapitre_id);

        // Rediriger vers la page précédente
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
