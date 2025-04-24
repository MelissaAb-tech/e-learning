<?php
class EtudiantController extends Controller
{
    public function dashboard()
    {
        $cours = $this->model('Cours')->getAll();

        // Si l'utilisateur est connecté, vérifier les inscriptions
        $coursInscrits = [];
        if (isset($_SESSION['user'])) {
            $inscriptionModel = $this->model('CoursInscription');
            foreach ($cours as $c) {
                $coursInscrits[$c['id']] = $inscriptionModel->estInscrit($_SESSION['user']['id'], $c['id']);
            }
        }

        $this->view('etudiant/dashboard', [
            'cours' => $cours,
            'coursInscrits' => $coursInscrits
        ]);
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
        $user = $userModel->getById($id);  // créer pour récupérer l'utilisateur par son ID

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
        
        // Récupérer l'utilisateur actuel avec toutes ses données
        $currentUser = $userModel->getById($id);
    
        // Récupérer les données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $age = $_POST['age'];
        $fonction = $_POST['fonction'];
        $email = $_POST['email'];
        $adresse = $_POST['adresse'];
        $telephone = $_POST['telephone'];
        
        // Gestion du mot de passe
        $password = $currentUser['password']; // Mot de passe actuel par défaut
        
        // Vérifier si un nouveau mot de passe a été soumis
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Si un nouveau mot de passe est fourni et qu'il correspond à la confirmation
        if (!empty($new_password) && $new_password === $confirm_password) {
            // Hasher le nouveau mot de passe
            $password = password_hash($new_password, PASSWORD_DEFAULT);
        } elseif (!empty($new_password) && $new_password !== $confirm_password) {
            // Si les mots de passe ne correspondent pas, définir un message d'erreur
            $_SESSION['error_message'] = "Les mots de passe ne correspondent pas !";
            header('Location: /e-learning-role-final/public/etudiant/mon-compte');
            exit;
        }
    
        // Gestion de la photo de profil - CORRECTION ICI
        $photo_profil = $currentUser['photo_profil']; // Utiliser la photo actuelle de la base de données
        
        if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] == 0) {
            // Nouveau nom pour l'image pour éviter les conflits
            $photo_profil = time() . '_' . $_FILES['photo_profil']['name'];
            $uploadDir = __DIR__ . '/../../public/images/';
            
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            move_uploaded_file($_FILES['photo_profil']['tmp_name'], $uploadDir . $photo_profil);
        }
    
        // Mettre à jour les informations dans la bd
        $userModel->updateInfo($id, $nom, $prenom, $age, $fonction, $email, $adresse, $telephone, $photo_profil, $password);
    
        // Mise à jour de la session
        $_SESSION['user']['nom'] = $nom;
        $_SESSION['user']['prenom'] = $prenom;
        $_SESSION['user']['age'] = $age;
        $_SESSION['user']['fonction'] = $fonction;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['adresse'] = $adresse;
        $_SESSION['user']['telephone'] = $telephone;
        $_SESSION['user']['photo_profil'] = $photo_profil;
        
        // Message de succès adapté
        if (!empty($new_password) && $new_password === $confirm_password) {
            $_SESSION['success_message'] = "Vos informations et votre mot de passe ont été mis à jour avec succès !";
        } else {
            $_SESSION['success_message'] = "Vos informations ont été mises à jour avec succès !";
        }
        
        // Rediriger vers la page "Mon compte"
        header('Location: /e-learning-role-final/public/etudiant/mon-compte');
        exit;
    }

    public function logout()
    {
        session_start();
        // Détruire toutes les variables de session
        session_unset();
        // Détruire la session
        session_destroy();
        // Rediriger l'utilisateur vers la page d'accueil
        header('Location: /e-learning-role-final/public');
        exit;
    }

    // Nouvelle méthode pour s'inscrire à un cours
    public function inscrire($cours_id)
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        // Vérifier que le cours existe
        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($cours_id);

        if (!$cours) {
            echo "Cours introuvable.";
            exit;
        }

        // Inscrire l'étudiant
        $this->model('CoursInscription')->inscrire($_SESSION['user']['id'], $cours_id);

        // Rediriger vers la page du cours
        header("Location: /e-learning-role-final/public/cours/voir/$cours_id");
        exit;
    }

    // Nouvelle méthode pour se désinscrire d'un cours
    public function desinscrire($cours_id)
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        // Vérifier que le cours existe
        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($cours_id);

        if (!$cours) {
            echo "Cours introuvable.";
            exit;
        }

        // Désinscrire l'étudiant
        $this->model('CoursInscription')->desinscrire($_SESSION['user']['id'], $cours_id);

        // Rediriger vers le dashboard
        header("Location: /e-learning-role-final/public/etudiant/dashboard");
        exit;
    }

    // Méthode pour gérer le feedback
    public function feedback()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier si l'utilisateur est connecté
            if (!isset($_SESSION['user'])) {
                header('Location: /e-learning-role-final/public/login');
                exit;
            }

            // Récupérer les données du formulaire
            $etudiant_id = $_SESSION['user']['id'];  // L'id de l'étudiant
            $rating = $_POST['rating'];  // Note de 1 à 5
            $commentaire = $_POST['commentaire'];  // Commentaire de l'étudiant

            // Ajouter le feedback dans la base de données via le modèle
            $feedbackModel = $this->model('FeedbackModel');
            $result = $feedbackModel->create($etudiant_id, $rating, $commentaire);
            
            if ($result) {
                // Ajouter un message de succès dans la session
                $_SESSION['feedback_success'] = "Votre feedback a bien été envoyé. Merci pour votre contribution !";
            }

            // Rediriger l'utilisateur vers le tableau de bord après l'envoi du feedback
            header("Location: /e-learning-role-final/public/etudiant/dashboard");
            exit;
        }
    }
}
