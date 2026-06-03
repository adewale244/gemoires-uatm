<?php
class ProfilController extends Controller {

    public function index() {
        $this->requireLogin();
        $db = Database::getInstance();
        $user = $db->findOne(
            "SELECT * FROM utilisateurs WHERE id = ?",
            [$_SESSION['user_id']]
        );
        $this->view('profil/index', ['user' => $user]);
    }

    public function update() {
        $this->requireLogin();

        $nom     = trim($_POST['nom'] ?? '');
        $prenom  = trim($_POST['prenom'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $filiere = trim($_POST['filiere'] ?? '');

        $db = Database::getInstance();

        // Changer mot de passe si demandé
        if (!empty($_POST['new_password'])) {
            $user = $db->findOne("SELECT * FROM utilisateurs WHERE id = ?", [$_SESSION['user_id']]);
            if (!password_verify($_POST['current_password'], $user['mot_de_passe'])) {
                $this->view('profil/index', ['user' => $user, 'error' => 'Mot de passe actuel incorrect.']);
                return;
            }
            $newHash = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
            $db->query(
                "UPDATE utilisateurs SET nom=?, prenom=?, email=?, filiere=?, mot_de_passe=? WHERE id=?",
                [$nom, $prenom, $email, $filiere, $newHash, $_SESSION['user_id']]
            );
        } else {
            $db->query(
                "UPDATE utilisateurs SET nom=?, prenom=?, email=?, filiere=? WHERE id=?",
                [$nom, $prenom, $email, $filiere, $_SESSION['user_id']]
            );
        }

        // Mettre à jour la session
        $_SESSION['nom']    = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['email']  = $email;

        $this->redirect('profil/index?success=1');
    }
}