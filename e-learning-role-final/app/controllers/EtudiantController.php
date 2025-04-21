<?php
class EtudiantController extends Controller
{
    public function dashboard()
    {
        $cours = $this->model('Cours')->getAll();
        $this->view('etudiant/dashboard', ['cours' => $cours]);
    }
    public function monCompte()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        // Récupérer les données utilisateur depuis la base de données
        $userModel = $this->model('User');
        $id = $_SESSION['user']['id'];
        $user = $userModel->getById($id);  // Méthode à créer pour récupérer l'utilisateur par son ID

        // Passer les données de l'utilisateur à la vue
        $this->view('etudiant/mon-compte', ['user' => $user]);
    }

    public function monComptePost()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        $userModel = $this->model('User');
        $id = $_SESSION['user']['id'];

        // Récupérer les données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $age = $_POST['age'];
        $fonction = $_POST['fonction'];
        $email = $_POST['email'];
        $adresse = $_POST['adresse'];
        $telephone = $_POST['telephone'];

        // Gérer la photo de profil
        $photo_profil = $_SESSION['user']['photo_profil']; // Garder l'ancienne photo si aucune nouvelle n'est téléchargée

        if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] == 0) {
            // Nouveau nom pour l'image pour éviter les conflits
            $photo_profil = time() . '_' . $_FILES['photo_profil']['name'];
            $uploadDir = 'public/images/';
            $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $uploadDir . $photo_profil;

            // Déplacer l'image téléchargée dans le répertoire approprié
            move_uploaded_file($_FILES['photo_profil']['tmp_name'], $uploadPath);
        }

        // Mettre à jour les informations dans la base de données
        $userModel->updateInfo($id, $nom, $prenom, $age, $fonction, $email, $adresse, $telephone, $photo_profil);

        // Mise à jour de la session avec les nouvelles informations
        $_SESSION['user']['nom'] = $nom;
        $_SESSION['user']['prenom'] = $prenom;
        $_SESSION['user']['age'] = $age;
        $_SESSION['user']['fonction'] = $fonction;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['adresse'] = $adresse;
        $_SESSION['user']['telephone'] = $telephone;
        $_SESSION['user']['photo_profil'] = $photo_profil;

        // Rediriger vers la page "Mon compte" avec les informations mises à jour
        header('Location: /e-learning-role-final/public/etudiant/mon-compte');
        exit;
    }
}
