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
            $coursModel->update(
                $id,
                $_POST['nom'],
                $_POST['professeur'],
                $_POST['contenu'],
                $_POST['niveau'],
                $_POST['duree'],
                $_POST['image']
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
        $this->view('admin/dashboard', ['cours' => $cours, 'etudiants' => $etudiants]);
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
                // Définir le répertoire de destination
                $targetDir = "../../public/images/";
                $photo_profil = time() . '_' . $_FILES['photo_profil']['name'];  // Nouveau nom unique 
                $targetFile = $targetDir . $photo_profil;

                // Vérifier si le répertoire existe sinon le créer
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);  // Créer le répertoire
                }

                // Déplacer l'image téléchargée dans le répertoire de destination
                move_uploaded_file($_FILES['photo_profil']['tmp_name'], $targetFile);
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
}
