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
     public function stats() {
    $this->requireLogin();
    if (!in_array($_SESSION['role'], [ROLE_ADMIN, ROLE_DIRECTEUR])) {
        $this->redirect('memoires/index');
    }

    $db = Database::getInstance();

    $data = [
        // Mémoires
        'total_memoires'    => $db->findOne("SELECT COUNT(*) as t FROM memoires")['t'],
        'total_valides'     => $db->findOne("SELECT COUNT(*) as t FROM memoires WHERE statut='valide'")['t'],
        'total_en_attente'  => $db->findOne("SELECT COUNT(*) as t FROM memoires WHERE statut='en_attente'")['t'],
        'total_rejetes'     => $db->findOne("SELECT COUNT(*) as t FROM memoires WHERE statut='rejete'")['t'],
        'total_anciens'     => $db->findOne("SELECT COUNT(*) as t FROM memoires WHERE type='ancien'")['t'],
        'total_nouveaux'    => $db->findOne("SELECT COUNT(*) as t FROM memoires WHERE type='nouveau'")['t'],

        // Utilisateurs
        'total_users'       => $db->findOne("SELECT COUNT(*) as t FROM utilisateurs")['t'],
        'total_profs'       => $db->findOne("SELECT COUNT(*) as t FROM utilisateurs WHERE role='professeur'")['t'],
        'total_diplomes'    => $db->findOne("SELECT COUNT(*) as t FROM utilisateurs WHERE role='diplome'")['t'],
        'total_consultants' => $db->findOne("SELECT COUNT(*) as t FROM utilisateurs WHERE role='consultant'")['t'],

        // Interactions
        'total_comments'    => $db->findOne("SELECT COUNT(*) as t FROM commentaires")['t'],
        'total_likes'       => $db->findOne("SELECT COUNT(*) as t FROM likes")['t'],
        'total_consultations' => $db->findOne("SELECT COUNT(*) as t FROM consultations")['t'],
        'total_favoris'     => $db->findOne("SELECT COUNT(*) as t FROM favoris")['t'],

        // Top mémoires
        'top_memoires'      => $db->findAll(
            "SELECT m.titre, m.filiere,
                    COUNT(DISTINCT c.id) as vues,
                    COUNT(DISTINCT l.id) as likes,
                    COUNT(DISTINCT cm.id) as comments
             FROM memoires m
             LEFT JOIN consultations c ON c.id_memoire = m.id
             LEFT JOIN likes l ON l.id_cible = m.id AND l.type_cible = 'memoire'
             LEFT JOIN commentaires cm ON cm.id_memoire = m.id
             WHERE m.statut = 'valide'
             GROUP BY m.id
             ORDER BY vues DESC
             LIMIT 5"
        ),

        // Par filière
        'par_filiere' => $db->findAll(
            "SELECT filiere, COUNT(*) as total
             FROM memoires WHERE statut='valide' AND filiere IS NOT NULL
             GROUP BY filiere ORDER BY total DESC"
        ),
    ];

    $this->view('admin/stats', $data);
}public function signalements() {
    $this->requireLogin();
    if (!in_array($_SESSION['role'], [ROLE_ADMIN, ROLE_DIRECTEUR])) {
        $this->redirect('memoires/index');
    }

    $db = Database::getInstance();
    $signalements = $db->findAll(
        "SELECT s.*, 
                u.nom as signaleur_nom, u.prenom as signaleur_prenom,
                c.contenu as commentaire_contenu,
                cu.nom as auteur_nom, cu.prenom as auteur_prenom
         FROM signalements s
         LEFT JOIN utilisateurs u ON s.id_user = u.id
         LEFT JOIN commentaires c ON s.id_commentaire = c.id
         LEFT JOIN utilisateurs cu ON c.id_user = cu.id
         ORDER BY s.date_signalement DESC"
    );

    $this->view('admin/signalements', ['signalements' => $signalements]);
}

public function traiterSignalement($id) {
    $this->requireLogin();
    if (!in_array($_SESSION['role'], [ROLE_ADMIN, ROLE_DIRECTEUR])) {
        $this->redirect('memoires/index');
    }

    $action = $_POST['action'] ?? '';
    $db = Database::getInstance();

    $signalement = $db->findOne("SELECT * FROM signalements WHERE id = ?", [$id]);

    if ($action === 'supprimer') {
        // Supprimer le commentaire
        $db->query("DELETE FROM commentaires WHERE id = ?", [$signalement['id_commentaire']]);
        $db->query("DELETE FROM signalements WHERE id_commentaire = ?", [$signalement['id_commentaire']]);
    } else {
        // Ignorer — juste marquer comme traité
        $db->query("UPDATE signalements SET statut = 'traite' WHERE id = ?", [$id]);
    }

    $this->redirect('admin/signalements');
}
}
