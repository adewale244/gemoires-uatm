<?php
class ProfesseurController extends Controller {

    public function dashboard() {
        $this->requireLogin();
        if (!in_array($_SESSION['role'], [ROLE_PROF, ROLE_ADMIN, ROLE_DIRECTEUR])) {
            $this->redirect('memoires/index');
        }

        $db = Database::getInstance();

        $memoires = $db->findAll(
            "SELECT m.*, u.nom, u.prenom, u.filiere
             FROM memoires m
             LEFT JOIN utilisateurs u ON m.id_etudiant = u.id
             WHERE m.id_professeur = ? AND m.statut = 'en_attente'
             ORDER BY m.created_at DESC",
            [$_SESSION['user_id']]
        );

        $historique = $db->findAll(
            "SELECT m.*, u.nom, u.prenom, w.decision, w.commentaire, w.date_action
             FROM memoires m
             LEFT JOIN utilisateurs u ON m.id_etudiant = u.id
             LEFT JOIN workflow w ON w.id_memoire = m.id AND w.id_acteur = ?
             WHERE m.id_professeur = ? AND m.statut != 'en_attente'
             ORDER BY w.date_action DESC",
            [$_SESSION['user_id'], $_SESSION['user_id']]
        );

        $this->view('professeur/dashboard', [
            'memoires'   => $memoires,
            'historique' => $historique
        ]);
    }

    public function valider($id) {
        $this->requireLogin();
        if (!in_array($_SESSION['role'], [ROLE_PROF, ROLE_ADMIN, ROLE_DIRECTEUR])) {
            $this->redirect('memoires/index');
        }

        $decision    = $_POST['decision'] ?? '';
        $commentaire = trim($_POST['commentaire'] ?? '');

        if (!in_array($decision, ['approuve', 'rejete', 'correction'])) {
            $this->redirect('professeur/dashboard');
        }

        $db = Database::getInstance();

        // Récupérer le mémoire
        $memoire = $db->findOne("SELECT * FROM memoires WHERE id = ?", [$id]);
        if (!$memoire) {
            $this->redirect('professeur/dashboard');
        }

        // Mettre à jour le statut
        $statut = $decision === 'approuve' ? 'valide' :
                 ($decision === 'rejete' ? 'rejete' : 'correction');

        $db->query(
            "UPDATE memoires SET statut = ? WHERE id = ?",
            [$statut, $id]
        );

        // Enregistrer dans workflow
        $db->query(
            "INSERT INTO workflow (id_memoire, id_acteur, etape, decision, commentaire)
             VALUES (?, ?, 'validation_prof', ?, ?)",
            [$id, $_SESSION['user_id'], $decision, $commentaire]
        );

        // Notifier l'étudiant
        $msg = match($decision) {
            'approuve'   => "🎉 Votre mémoire \"{$memoire['titre']}\" a été validé et est maintenant disponible sur la plateforme !",
            'rejete'     => "❌ Votre mémoire \"{$memoire['titre']}\" a été rejeté. Motif : $commentaire",
            'correction' => "⚠️ Des corrections sont demandées pour votre mémoire \"{$memoire['titre']}\" : $commentaire",
        };

        $db->query(
            "INSERT INTO notifications (id_destinataire, message) VALUES (?, ?)",
            [$memoire['id_etudiant'], $msg]
        );

        $this->redirect('professeur/dashboard');
    }
}