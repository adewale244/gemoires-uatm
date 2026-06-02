<?php
class AuthController extends Controller {

    // Afficher page login
    public function index() {
        if ($this->isLoggedIn()) {
            $this->redirectDashboard();
        }
        $this->view('auth/login');
    }

    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirectDashboard();
        }
        $this->view('auth/login');
    }

    // Traiter connexion
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('auth/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->view('auth/login', ['error' => 'Veuillez remplir tous les champs.']);
            return;
        }

        $db = Database::getInstance();
        $user = $db->findOne(
            "SELECT * FROM utilisateurs WHERE email = ? AND statut = 1",
            [$email]
        );

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['nom']      = $user['nom'];
            $_SESSION['prenom']   = $user['prenom'];
            $_SESSION['email']    = $user['email'];
            $_SESSION['role']     = $user['role'];
            $_SESSION['filiere']  = $user['filiere'];

            $this->redirectDashboard();
        } else {
            $this->view('auth/login', ['error' => 'Email ou mot de passe incorrect.']);
        }
    }

    // Déconnexion
    public function logout() {
        session_destroy();
        $this->redirect('auth/login');
    }

    // Rediriger selon le rôle
    private function redirectDashboard() {
        switch ($_SESSION['role']) {
            case ROLE_ADMIN:
            case ROLE_DIRECTEUR:
                $this->redirect('admin/dashboard');
                break;
            case ROLE_PROF:
                $this->redirect('professeur/dashboard');
                break;
            default:
                $this->redirect('memoires/index');
        }
    }
}