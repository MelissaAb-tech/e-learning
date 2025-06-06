<?php
class AdminController extends Controller
{

    public function ajouter()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imageName = null;

            if (!empty($_FILES['image']['name'])) {
                $targetDir = "../public/images/";
                $imageName = basename($_FILES['image']['name']);
                $targetFile = $targetDir . $imageName;

                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
            }

            $this->model('Cours')->create(
                $_POST['nom'],
                $_POST['professeur'],
                $_POST['contenu'],
                $_POST['niveau'],
                $_POST['duree'],
                $imageName
            );

            header('Location: /e-learning-role-final/public/admin/dashboard');
        } else {
            $this->view('admin/ajouter');
        }
    }

    public function modifier($id)
    {
        $coursModel = $this->model('Cours');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer l'image existante
            $image = $_POST['image'] ?? '';

            // Si une nouvelle image est uploadée
            if (!empty($_FILES['nouvelle_image']['name'])) {
                $targetDir = "../public/images/";
                $image = basename($_FILES['nouvelle_image']['name']);
                $targetFile = $targetDir . $image;

                move_uploaded_file($_FILES['nouvelle_image']['tmp_name'], $targetFile);
            }

            $coursModel->update(
                $id,
                $_POST['nom'],
                $_POST['professeur'],
                $_POST['contenu'],
                $_POST['niveau'],
                $_POST['duree'],
                $image
            );

            header('Location: /e-learning-role-final/public/admin/dashboard');
        } else {
            $cours = $coursModel->getById($id);
            $this->view('admin/modifier', ['cours' => $cours]);
        }
    }

    public function supprimer($id)
    {
        $this->model('Cours')->delete($id);
        header('Location: /e-learning-role-final/public/admin/dashboard');
    }
    public function voir($id)
    {
        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pdf = $cours['pdf'];
            $video = $cours['video'];

            // PDF upload
            if (!empty($_FILES['pdf']['name'])) {
                $pdf = basename($_FILES['pdf']['name']);
                move_uploaded_file($_FILES['pdf']['tmp_name'], "../public/pdfs/" . $pdf);
            }

            // vidéo fichier ou lien
            if (!empty($_FILES['video_file']['name'])) {
                $video = basename($_FILES['video_file']['name']);
                move_uploaded_file($_FILES['video_file']['tmp_name'], "../public/videos/" . $video);
            } elseif (!empty($_POST['video'])) {
                $video = $_POST['video'];
            }

            // Mise à jour
            $coursModel->updateFichiers($id, $pdf, $video);
            header("Location: /e-learning-role-final/public/admin/voir/$id");
        }

        $this->view('admin/voir', ['cours' => $cours]);
    }

    public function dashboard()
    {
        $cours = $this->model('Cours')->getAll();
        $etudiants = $this->model('User')->getAllEtudiants();

        // Récupérer la moyenne des notes et le nombre de feedbacks
        $feedbackModel = $this->model('FeedbackModel');
        $moyenneNotes = $feedbackModel->getMoyenneNotes();
        $nombreFeedbacks = $feedbackModel->getNombreFeedbacks();

        $this->view('admin/dashboard', [
            'cours' => $cours,
            'etudiants' => $etudiants,
            'moyenneNotes' => $moyenneNotes,
            'nombreFeedbacks' => $nombreFeedbacks
        ]);
    }

    public function etudiantAjouter()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $age = $_POST['age'];
            $adresse = $_POST['adresse'];
            $fonction = $_POST['fonction'];
            $telephone = $_POST['telephone'];

            $photo_profil = null;
            if (!empty($_FILES['photo_profil']['name'])) {
                // Définir le répertoire de destination avec un chemin absolu
                $uploadDir = __DIR__ . '/../../public/images/';

                // Nouveau nom unique pour éviter les collisions
                $photo_profil = time() . '_' . basename($_FILES['photo_profil']['name']);

                // Vérifier si le répertoire existe sinon le créer
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Déplacer l'image téléchargée
                move_uploaded_file($_FILES['photo_profil']['tmp_name'], $uploadDir . $photo_profil);
            }

            // Appeler la méthode pour créer l'étudiant dans la bd
            $this->model('User')->createEtudiant($prenom, $nom, $email, $password, $age, $adresse, $fonction, $telephone, $photo_profil);

            // Rediriger l'admin vers le tableau de bord après ajout de l'étudiant
            header('Location: /e-learning-role-final/public/admin/dashboard');
            exit;
        }

        $this->view('admin/etudiant_ajouter');
    }

    public function etudiantSupprimer($id)
    {
        $this->model('User')->delete($id);
        header('Location: /e-learning-role-final/public/admin/dashboard');
        exit;
    }
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header('Location: /e-learning-role-final/public');  // Rediriger vers la page de d'acceuil
        exit;
    }
    public function etudiantModifier($id)
    {
        $userModel = $this->model('User');
        $etudiant = $userModel->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $prenom = $_POST['prenom'];
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $age = $_POST['age'];
            $adresse = $_POST['adresse'];
            $fonction = $_POST['fonction'];
            $telephone = $_POST['telephone'];
            $photo_profil = $etudiant['photo_profil'];  // Garder l'ancienne photo 

            // Gérer la photo de profil
            if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) {
                // Nouveau nom pour l'image pour éviter les conflits
                $photo_profil = time() . '_' . basename($_FILES['photo_profil']['name']);
                $uploadDir = __DIR__ . '/../../public/images/';

                // Vérifier si le dossier existe, sinon le créer
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                move_uploaded_file($_FILES['photo_profil']['tmp_name'], $uploadDir . $photo_profil);
            }

            // Vérifier si un mot de passe est fourni et le hacher 
            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hacher le mot de passe
            } else {
                $password = $etudiant['password'];  // Garder l'ancien mot de passe si aucun nouveau 
            }

            // Mettre à jour les informations de l'étudiant dans la bd
            $userModel->updateInfo($id, $nom, $prenom, $age, $fonction, $email, $adresse, $telephone, $photo_profil, $password);

            header('Location: /e-learning-role-final/public/admin/dashboard');
            exit;
        }

        $this->view('admin/etudiant_modifier', ['etudiant' => $etudiant]);
    }

    public function feedbacks()
    {
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        // Récupérer tous les feedbacks
        $feedbackModel = $this->model('FeedbackModel');
        $feedbacks = $feedbackModel->getAllFeedbacks();

        // Afficher la vue avec les feedbacks
        $this->view('admin/feedbacks', [
            'feedbacks' => $feedbacks
        ]);
    }

    // voir les feedbacks d'un cours spécifique
    public function coursFeedbacks($cours_id)
    {
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
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

        // Récupérer tous les feedbacks pour ce cours
        $feedbackModel = $this->model('CoursFeedback');
        $feedbacks = $feedbackModel->getByCours($cours_id);
        $moyenne_notes = $feedbackModel->getMoyenneNotesParCours($cours_id);

        // Afficher la vue avec les feedbacks
        $this->view('admin/cours_feedbacks', [
            'cours' => $cours,
            'feedbacks' => $feedbacks,
            'moyenne_notes' => $moyenne_notes
        ]);
    }
}
