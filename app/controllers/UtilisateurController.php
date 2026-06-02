<?php
class UtilisateurController extends Controller {

    // Liste des utilisateurs
   public function index() {
    $this->requireLogin();
    
    // Vérifier admin OU directeur
    if (!in_array($_SESSION['role'], [ROLE_ADMIN, ROLE_DIRECTEUR])) {
        $this->redirect('auth/login');
    }

    $db = Database::getInstance();
    $users = $db->findAll("SELECT * FROM utilisateurs ORDER BY created_at DESC");
    $this->view('admin/utilisateurs', ['users' => $users]);
}
    // Formulaire création
    public function create() {
        $this->requireLogin();
        $this->view('admin/create_user', []);
    }

    // Enregistrer utilisateur
    public function store() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('utilisateur/create');
        }

        $nom     = trim($_POST['nom'] ?? '');
        $prenom  = trim($_POST['prenom'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $role    = $_POST['role'] ?? '';
        $filiere = trim($_POST['filiere'] ?? '');
        $niveau  = trim($_POST['niveau'] ?? '');
        $password = password_hash($_POST['password'] ?? 'uatm2025', PASSWORD_BCRYPT);

        if (empty($nom) || empty($prenom) || empty($email) || empty($role)) {
            $this->view('admin/create_user', ['error' => 'Tous les champs obligatoires doivent être remplis.']);
            return;
        }

        $db = Database::getInstance();

        // Vérifier email unique
        $exist = $db->findOne("SELECT id FROM utilisateurs WHERE email = ?", [$email]);
        if ($exist) {
            $this->view('admin/create_user', ['error' => 'Cet email est déjà utilisé.']);
            return;
        }

        $db->query(
            "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, filiere, niveau) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$nom, $prenom, $email, $password, $role, $filiere, $niveau]
        );

        $this->redirect('utilisateur/index?success=1');
    }

    // Désactiver compte
    public function toggle($id) {
        $this->requireLogin();
        $db = Database::getInstance();
        $user = $db->findOne("SELECT * FROM utilisateurs WHERE id = ?", [$id]);
        if ($user) {
            $newStatut = $user['statut'] == 1 ? 0 : 1;
            $db->query("UPDATE utilisateurs SET statut = ? WHERE id = ?", [$newStatut, $id]);
        }
        $this->redirect('utilisateur/index');
    }
}