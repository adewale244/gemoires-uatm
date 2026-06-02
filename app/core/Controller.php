<?php
class Controller {

    // Charger une vue
    protected function view($view, $data = []) {
        extract($data);
        $viewFile = 'views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Vue introuvable : " . $view);
        }
    }

    // Vérifier si connecté
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Vérifier le rôle
    protected function hasRole($role) {
        return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }

    // Redirection
    protected function redirect($url) {
        header('Location: ' . APP_URL . '/' . $url);
        exit();
    }

    // Bloquer accès si non connecté
    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }

    // Bloquer accès si mauvais rôle
    protected function requireRole($role) {
        $this->requireLogin();
        if (!$this->hasRole($role)) {
            $this->redirect('auth/login');
        }
    }
}