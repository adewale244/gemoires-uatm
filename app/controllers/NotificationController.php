<?php
class NotificationController extends Controller {

    // Liste des notifications de l'utilisateur connecté
    public function index() {
        $this->requireLogin();
        $db = Database::getInstance();

        $notifications = $db->findAll(
            "SELECT * FROM notifications 
             WHERE id_destinataire = ? 
             ORDER BY date_notification DESC",
            [$_SESSION['user_id']]
        );

        // Marquer toutes comme lues
        $db->query(
            "UPDATE notifications SET lu = 1 WHERE id_destinataire = ?",
            [$_SESSION['user_id']]
        );

        $this->view('notifications/index', ['notifications' => $notifications]);
    }

    // Compter les non lues (appelé en AJAX)
    public function count() {
        $this->requireLogin();
        $db = Database::getInstance();

        $result = $db->findOne(
            "SELECT COUNT(*) as total FROM notifications 
             WHERE id_destinataire = ? AND lu = 0",
            [$_SESSION['user_id']]
        );

        header('Content-Type: application/json');
        echo json_encode(['count' => $result['total']]);
        exit();
    }

    // Supprimer une notification
    public function supprimer($id) {
        $this->requireLogin();
        $db = Database::getInstance();
        $db->query(
            "DELETE FROM notifications WHERE id = ? AND id_destinataire = ?",
            [$id, $_SESSION['user_id']]
        );
        $this->redirect('notification/index');
    }
}