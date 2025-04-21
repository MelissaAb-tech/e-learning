<?php
class AuthController extends Controller
{
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
            // ne pas stocker tout $user (ex : password hash)
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
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $this->model('User')->create($nom, $email, $password);
        header("Location: /e-learning-role-final/public/");
    }
}
