<?php
class MemoiresController extends Controller {

    // Liste tous les mémoires validés
    public function index() {
        $this->requireLogin();
        $db = Database::getInstance();

        $where = "WHERE m.statut = 'valide'";
        $params = [];

        // Filtres recherche
        if (!empty($_GET['q'])) {
            $where .= " AND (m.titre LIKE ? OR m.resume LIKE ?)";
            $q = '%' . $_GET['q'] . '%';
            $params[] = $q;
            $params[] = $q;
        }
        if (!empty($_GET['filiere'])) {
            $where .= " AND m.filiere = ?";
            $params[] = $_GET['filiere'];
        }
        if (!empty($_GET['annee'])) {
            $where .= " AND m.annee_academique = ?";
            $params[] = $_GET['annee'];
        }
        if (!empty($_GET['niveau'])) {
            $where .= " AND m.niveau = ?";
            $params[] = $_GET['niveau'];
        }

        $memoires = $db->findAll(
            "SELECT m.*, u.nom, u.prenom, u.filiere as filiere_auteur,
                    p.nom as prof_nom, p.prenom as prof_prenom,
                    (SELECT COUNT(*) FROM likes WHERE id_cible = m.id AND type_cible = 'memoire') as total_likes,
                    (SELECT COUNT(*) FROM commentaires WHERE id_memoire = m.id) as total_comments,
                    (SELECT COUNT(*) FROM consultations WHERE id_memoire = m.id) as total_vues
             FROM memoires m
             LEFT JOIN utilisateurs u ON m.id_etudiant = u.id
             LEFT JOIN utilisateurs p ON m.id_professeur = p.id
             $where
             ORDER BY m.created_at DESC",
            $params
        );

        $filieres = $db->findAll("SELECT DISTINCT filiere FROM memoires WHERE filiere IS NOT NULL ORDER BY filiere");

        $this->view('memoires/index', [
            'memoires' => $memoires,
            'filieres' => $filieres
        ]);
    }

    // Voir un mémoire (consultation PDF)
    public function voir($id) {
        $this->requireLogin();
        $db = Database::getInstance();

        $memoire = $db->findOne(
            "SELECT m.*, u.nom, u.prenom, p.nom as prof_nom, p.prenom as prof_prenom
             FROM memoires m
             LEFT JOIN utilisateurs u ON m.id_etudiant = u.id
             LEFT JOIN utilisateurs p ON m.id_professeur = p.id
             WHERE m.id = ? AND m.statut = 'valide'",
            [$id]
        );

        if (!$memoire) {
            $this->redirect('memoires/index');
        }

        // Enregistrer consultation
        $db->query(
            "INSERT INTO consultations (id_user, id_memoire) VALUES (?, ?)",
            [$_SESSION['user_id'], $id]
        );

        $commentaires = $db->findAll(
            "SELECT c.*, u.nom, u.prenom,
                    (SELECT COUNT(*) FROM likes WHERE id_cible = c.id AND type_cible = 'commentaire') as total_likes
             FROM commentaires c
             LEFT JOIN utilisateurs u ON c.id_user = u.id
             WHERE c.id_memoire = ?
             ORDER BY c.date_comment DESC",
            [$id]
        );

        $liked = $db->findOne(
            "SELECT id FROM likes WHERE id_user = ? AND id_cible = ? AND type_cible = 'memoire'",
            [$_SESSION['user_id'], $id]
        );

        $favori = $db->findOne(
            "SELECT id FROM favoris WHERE id_user = ? AND id_memoire = ?",
            [$_SESSION['user_id'], $id]
        );

        $this->view('memoires/voir', [
            'memoire'     => $memoire,
            'commentaires' => $commentaires,
            'liked'       => $liked,
            'favori'      => $favori
        ]);
    }

    // Soumettre un mémoire (étudiant diplômé)
    public function soumettre() {
        $this->requireLogin();
        if (!in_array($_SESSION['role'], [ROLE_DIPLOME, ROLE_DIRECTEUR, ROLE_ADMIN])) {
            $this->redirect('memoires/index');
        }
        $db = Database::getInstance();
        $profs = $db->findAll("SELECT id, nom, prenom FROM utilisateurs WHERE role = 'professeur' AND statut = 1");
        $this->view('memoires/soumettre', ['profs' => $profs]);
    }

    // Enregistrer soumission
    public function store() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('memoires/soumettre');
        }

        $titre   = trim($_POST['titre'] ?? '');
        $resume  = trim($_POST['resume'] ?? '');
        $filiere = trim($_POST['filiere'] ?? '');
        $niveau  = $_POST['niveau'] ?? '';
        $annee   = trim($_POST['annee_academique'] ?? '');
        $prof_id = $_POST['id_professeur'] ?? null;

        if (empty($titre) || empty($filiere) || empty($niveau)) {
            $this->redirect('memoires/soumettre');
        }

        // Upload fichier
        $url_fichier = '';
        if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0) {
            $ext = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['pdf', 'doc', 'docx'])) {
                $this->redirect('memoires/soumettre');
            }
            $filename = uniqid('mem_') . '.' . $ext;
            $dest = UPLOAD_PATH . $filename;
            if (move_uploaded_file($_FILES['fichier']['tmp_name'], $dest)) {
                $url_fichier = $filename;
            }
        }

        $db = Database::getInstance();
        $db->query(
            "INSERT INTO memoires (titre, resume, filiere, niveau, annee_academique, url_fichier, id_etudiant, id_professeur, statut, type)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'en_attente', 'nouveau')",
            [$titre, $resume, $filiere, $niveau, $annee, $url_fichier, $_SESSION['user_id'], $prof_id]
        );

        $id = $db->lastInsertId();

        // Notifier le professeur
        if ($prof_id) {
            $db->query(
                "INSERT INTO notifications (id_destinataire, message) VALUES (?, ?)",
                [$prof_id, "Nouveau mémoire soumis : \"$titre\" par {$_SESSION['prenom']} {$_SESSION['nom']}. Veuillez le valider."]
            );
        }

        $this->redirect('memoires/voir/' . $id);
    }

    // Liker un mémoire
    public function liker($id) {
        $this->requireLogin();
        $db = Database::getInstance();

        $exist = $db->findOne(
            "SELECT id FROM likes WHERE id_user = ? AND id_cible = ? AND type_cible = 'memoire'",
            [$_SESSION['user_id'], $id]
        );

        if ($exist) {
            $db->query("DELETE FROM likes WHERE id = ?", [$exist['id']]);
        } else {
            $db->query(
                "INSERT INTO likes (id_user, id_cible, type_cible) VALUES (?, ?, 'memoire')",
                [$_SESSION['user_id'], $id]
            );
        }
        $this->redirect('memoires/voir/' . $id);
    }

    // Commenter
    public function commenter($id) {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('memoires/voir/' . $id);
        }
        $contenu = trim($_POST['contenu'] ?? '');
        if (!empty($contenu)) {
            $db = Database::getInstance();
            $db->query(
                "INSERT INTO commentaires (contenu, id_user, id_memoire) VALUES (?, ?, ?)",
                [$contenu, $_SESSION['user_id'], $id]
            );
        }
        $this->redirect('memoires/voir/' . $id);
    }

    // Ajouter/retirer favori
    public function favori($id) {
        $this->requireLogin();
        $db = Database::getInstance();
        $exist = $db->findOne(
            "SELECT id FROM favoris WHERE id_user = ? AND id_memoire = ?",
            [$_SESSION['user_id'], $id]
        );
        if ($exist) {
            $db->query("DELETE FROM favoris WHERE id = ?", [$exist['id']]);
        } else {
            $db->query(
                "INSERT INTO favoris (id_user, id_memoire) VALUES (?, ?)",
                [$_SESSION['user_id'], $id]
            );
        }
        $this->redirect('memoires/voir/' . $id);
    }
    // Mes favoris
    public function favoris() {
        $this->requireLogin();
        $db = Database::getInstance();
        $favoris = $db->findAll(
            "SELECT m.*, u.nom, u.prenom, f.date_ajout
             FROM favoris f
             LEFT JOIN memoires m ON f.id_memoire = m.id
             LEFT JOIN utilisateurs u ON m.id_etudiant = u.id
             WHERE f.id_user = ?
             ORDER BY f.date_ajout DESC",
            [$_SESSION['user_id']]
        );
        $this->view('memoires/favoris', ['favoris' => $favoris]);
    }

    // Servir le PDF sécurisé (sans téléchargement)
    public function pdf($filename) {
        $this->requireLogin();
        $file = UPLOAD_PATH . basename($filename);
        if (!file_exists($file)) {
            http_response_code(404);
            die("Fichier introuvable.");
        }
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="memoire.pdf"');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: no-store');
        readfile($file);
        exit();
    }
}