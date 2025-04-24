<?php
class ChapitreController extends Controller
{
    public function ajouter($cours_id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'];
            $description = $_POST['description'];

            // Tableaux pour stocker les fichiers PDF, MP4 et liens YouTube
            $pdfs = [];
            $videos = [];
            $youtube_links = [];

            // Traitement des PDFs
            if (isset($_FILES['pdfs'])) {
                $pdf_count = count($_FILES['pdfs']['name']);
                
                for ($i = 0; $i < $pdf_count; $i++) {
                    if (!empty($_FILES['pdfs']['name'][$i])) {
                        $pdf_name = basename($_FILES['pdfs']['name'][$i]);
                        $target_file = "../public/pdfs/" . $pdf_name;
                        
                        if (move_uploaded_file($_FILES['pdfs']['tmp_name'][$i], $target_file)) {
                            $pdfs[] = $pdf_name;
                        }
                    }
                }
            }

            // Traitement des fichiers vidéo MP4
            if (isset($_FILES['videos'])) {
                $video_count = count($_FILES['videos']['name']);
                
                for ($i = 0; $i < $video_count; $i++) {
                    if (!empty($_FILES['videos']['name'][$i])) {
                        $video_name = basename($_FILES['videos']['name'][$i]);
                        $target_file = "../public/videos/" . $video_name;
                        
                        if (move_uploaded_file($_FILES['videos']['tmp_name'][$i], $target_file)) {
                            $videos[] = $video_name;
                        }
                    }
                }
            }

            // Traitement des liens YouTube
            if (isset($_POST['youtube_links'])) {
                foreach ($_POST['youtube_links'] as $link) {
                    if (!empty($link)) {
                        $youtube_links[] = $link;
                    }
                }
            }

            // Créer le chapitre avec tous les fichiers
            $this->model('Chapitre')->create($cours_id, $titre, $description, $pdfs, $videos, $youtube_links);
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
    
    public function modifier($id, $cours_id)
    {
        // Récupération du chapitre existant
        $chapitreModel = $this->model('Chapitre');
        $chapitre = $chapitreModel->getById($id);
        
        if (!$chapitre) {
            echo "Chapitre introuvable.";
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'];
            $description = $_POST['description'];

            // Tableaux pour stocker les fichiers
            $pdfs = [];
            $videos = [];
            $youtube_links = [];

            // Conserver les PDFs existants si demandé
            if (isset($_POST['keep_pdfs'])) {
                foreach ($_POST['keep_pdfs'] as $pdf_id) {
                    foreach ($chapitre['pdfs'] as $pdf) {
                        if ($pdf['id'] == $pdf_id) {
                            $pdfs[] = $pdf['pdf'];
                            break;
                        }
                    }
                }
            }

            // Traitement des nouveaux PDFs
            if (isset($_FILES['pdfs'])) {
                $pdf_count = count($_FILES['pdfs']['name']);
                
                for ($i = 0; $i < $pdf_count; $i++) {
                    if (!empty($_FILES['pdfs']['name'][$i])) {
                        $pdf_name = basename($_FILES['pdfs']['name'][$i]);
                        $target_file = "../public/pdfs/" . $pdf_name;
                        
                        if (move_uploaded_file($_FILES['pdfs']['tmp_name'][$i], $target_file)) {
                            $pdfs[] = $pdf_name;
                        }
                    }
                }
            }

            // Conserver les vidéos existantes si demandé
            if (isset($_POST['keep_videos'])) {
                foreach ($_POST['keep_videos'] as $video_id) {
                    foreach ($chapitre['videos'] as $video) {
                        if ($video['id'] == $video_id) {
                            if ($video['est_youtube'] == 1) {
                                $youtube_links[] = $video['video'];
                            } else {
                                $videos[] = $video['video'];
                            }
                            break;
                        }
                    }
                }
            }

            // Traitement des nouveaux fichiers vidéo MP4
            if (isset($_FILES['videos'])) {
                $video_count = count($_FILES['videos']['name']);
                
                for ($i = 0; $i < $video_count; $i++) {
                    if (!empty($_FILES['videos']['name'][$i])) {
                        $video_name = basename($_FILES['videos']['name'][$i]);
                        $target_file = "../public/videos/" . $video_name;
                        
                        if (move_uploaded_file($_FILES['videos']['tmp_name'][$i], $target_file)) {
                            $videos[] = $video_name;
                        }
                    }
                }
            }

            // Traitement des nouveaux liens YouTube
            if (isset($_POST['youtube_links'])) {
                foreach ($_POST['youtube_links'] as $link) {
                    if (!empty($link)) {
                        $youtube_links[] = $link;
                    }
                }
            }

            // Mettre à jour le chapitre avec tous les fichiers
            $chapitreModel->update($id, $titre, $description, $pdfs, $videos, $youtube_links);
            header("Location: /e-learning-role-final/public/cours/voir/$cours_id");
            exit;
        }

        $this->view('admin/modifier_chapitre', [
            'chapitre' => $chapitre,
            'cours_id' => $cours_id
        ]);
    }
}