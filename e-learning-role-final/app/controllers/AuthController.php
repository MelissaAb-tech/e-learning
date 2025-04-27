<?php
class AuthController extends Controller
{
    public function home()
    {
        $this->view('home');
    }

    public function login()
    {
        $this->view('auth/login');
    }

    public function loginPost()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = $this->model('User')->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // ne pas stocker tout 
            $_SESSION['user'] = [
                'id' => $user['id'],
                'nom' => $user['nom'],
                'email' => $user['email'],
                'role' => $user['role']
            ];

            //redirection selon le rôle
            $redirect = $user['role'] === 'admin' ? 'admin/dashboard' : 'etudiant/dashboard';
            header("Location: /e-learning-role-final/public/$redirect");
            exit;
        } else {
            $this->view('auth/login', ['error' => 'Email ou mot de passe incorrect']);
        }
    }


    public function register()
    {
        $this->view('auth/register');
    }

    public function registerPost()
    {
        $prenom = $_POST['prenom'];
        $nom = $_POST['nom'];
        $age = $_POST['age'];
        $fonction = $_POST['fonction'];
        $adresse = $_POST['adresse'];
        $telephone = $_POST['telephone'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Gestion de l'image de profil
        $photo_profil = null;
        if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['photo_profil']['tmp_name'];
            $imageName = time() . '_' . basename($_FILES['photo_profil']['name']);
            $uploadDir = __DIR__ . '/../../public/images/';

            // Vérifier si le dossier existe, sinon le créer
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($imageTmpPath, $uploadDir . $imageName)) {
                $photo_profil = $imageName;
            }
        }

        // créer l'utilisateur 
        $this->model('User')->create([
            'prenom' => $prenom,
            'nom' => $nom,
            'age' => $age,
            'fonction' => $fonction,
            'adresse' => $adresse,
            'telephone' => $telephone,
            'email' => $email,
            'password' => $password,
            'photo_profil' => $photo_profil
        ]);

        header("Location: /e-learning-role-final/public/");
    }
}
