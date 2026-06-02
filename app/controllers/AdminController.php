<?php
class AdminController extends Controller {

    public function dashboard() {
        $this->requireLogin();
        if (!in_array($_SESSION['role'], [ROLE_ADMIN, ROLE_DIRECTEUR])) {
            $this->redirect('memoires/index');
        }

        $db = Database::getInstance();

        $data = [
            'total_memoires'    => $db->findOne("SELECT COUNT(*) as total FROM memoires")['total'],
            'total_users'       => $db->findOne("SELECT COUNT(*) as total FROM utilisateurs")['total'],
            'en_attente'        => $db->findOne("SELECT COUNT(*) as total FROM memoires WHERE statut='en_attente'")['total'],
            'total_valides'     => $db->findOne("SELECT COUNT(*) as total FROM memoires WHERE statut='valide'")['total'],
            'derniers_memoires' => $db->findAll("SELECT m.*, u.nom, u.prenom FROM memoires m LEFT JOIN utilisateurs u ON m.id_etudiant = u.id ORDER BY m.created_at DESC LIMIT 5"),
            'derniers_users'    => $db->findAll("SELECT * FROM utilisateurs ORDER BY created_at DESC LIMIT 5"),
        ];

        $this->view('admin/dashboard', $data);
    }
}