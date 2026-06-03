<?php
class UploadController extends Controller {

    public function index() {
        $this->requireLogin();
        if (!in_array($_SESSION['role'], [ROLE_ADMIN, ROLE_DIRECTEUR])) {
            $this->redirect('memoires/index');
        }
        $db = Database::getInstance();
        $profs = $db->findAll("SELECT id, nom, prenom FROM utilisateurs WHERE role = 'professeur' AND statut = 1");
        $this->view('admin/upload', ['profs' => $profs]);
    }

    // Upload unitaire
    public function store() {
        $this->requireLogin();
        if (!in_array($_SESSION['role'], [ROLE_ADMIN, ROLE_DIRECTEUR])) {
            $this->redirect('memoires/index');
        }

        $titre   = trim($_POST['titre'] ?? '');
        $resume  = trim($_POST['resume'] ?? '');
        $filiere = trim($_POST['filiere'] ?? '');
        $niveau  = $_POST['niveau'] ?? '';
        $annee   = trim($_POST['annee_academique'] ?? '');
        $auteur  = trim($_POST['auteur'] ?? '');
        $prof_id = $_POST['id_professeur'] ?? null;

        $url_fichier = '';
        if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0) {
            $ext = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['pdf', 'doc', 'docx'])) {
                $this->redirect('upload/index');
            }
            $filename = uniqid('anc_') . '.' . $ext;
            if (move_uploaded_file($_FILES['fichier']['tmp_name'], UPLOAD_PATH . $filename)) {
                $url_fichier = $filename;
            }
        }

        $db = Database::getInstance();
        $etudiant_id = null;

        if (!empty($auteur)) {
            $parts  = explode(' ', $auteur, 2);
            $prenom = $parts[0];
            $nom    = $parts[1] ?? '';
            $email  = 'ancien_' . uniqid() . '@uatm.bj';
            $db->query(
                "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, filiere, statut)
                 VALUES (?, ?, ?, ?, 'diplome', ?, 0)",
                [$nom, $prenom, $email, password_hash('uatm2025', PASSWORD_BCRYPT), $filiere]
            );
            $etudiant_id = $db->lastInsertId();
        }

        $db->query(
            "INSERT INTO memoires (titre, resume, filiere, niveau, annee_academique, url_fichier, id_etudiant, id_professeur, statut, type)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'valide', 'ancien')",
            [$titre, $resume, $filiere, $niveau, $annee, $url_fichier, $etudiant_id, $prof_id]
        );

        $this->redirect('upload/index?success=1');
    }

    // Import massif Excel
    public function masse() {
        $this->requireLogin();
        if (!in_array($_SESSION['role'], [ROLE_ADMIN, ROLE_DIRECTEUR])) {
            $this->redirect('memoires/index');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('upload/index');
        }

        if (!isset($_FILES['excel']) || $_FILES['excel']['error'] !== 0) {
            $this->redirect('upload/index?error=excel');
        }

        $ext = strtolower(pathinfo($_FILES['excel']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['xlsx', 'xls', 'csv'])) {
            $this->redirect('upload/index?error=format');
        }

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['excel']['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet();
            $rows  = $sheet->toArray();

            $db = Database::getInstance();
            $count = 0;

            // Ignorer la première ligne (en-têtes)
            foreach (array_slice($rows, 1) as $row) {
                $titre   = trim($row[0] ?? '');
                $auteur  = trim($row[1] ?? '');
                $filiere = trim($row[2] ?? '');
                $niveau  = trim($row[3] ?? '');
                $annee   = trim($row[4] ?? '');
                $resume  = trim($row[5] ?? '');

                if (empty($titre)) continue;

                $etudiant_id = null;
                if (!empty($auteur)) {
                    $parts  = explode(' ', $auteur, 2);
                    $prenom = $parts[0];
                    $nom    = $parts[1] ?? '';
                    $email  = 'ancien_' . uniqid() . '@uatm.bj';
                    $db->query(
                        "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, filiere, statut)
                         VALUES (?, ?, ?, ?, 'diplome', ?, 0)",
                        [$nom, $prenom, $email, password_hash('uatm2025', PASSWORD_BCRYPT), $filiere]
                    );
                    $etudiant_id = $db->lastInsertId();
                }

                $db->query(
                    "INSERT INTO memoires (titre, resume, filiere, niveau, annee_academique, url_fichier, id_etudiant, statut, type)
                     VALUES (?, ?, ?, ?, ?, '', ?, 'valide', 'ancien')",
                    [$titre, $resume, $filiere, $niveau, $annee, $etudiant_id]
                );
                $count++;
            }

            $this->redirect('upload/index?success_masse=' . $count);

        } catch (Exception $e) {
            $this->redirect('upload/index?error=lecture');
        }
    }
}