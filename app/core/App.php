<?php
class App {
    private $controller = 'AuthController';
    private $method = 'index';
    private $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Controller
        if (!empty($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerFile = 'app/controllers/' . $controllerName . '.php';
            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                require_once $controllerFile;
                unset($url[0]);
            } else {
                $this->notFound();
                return;
            }
        } else {
            require_once 'app/controllers/AuthController.php';
        }

        $controller = new $this->controller();

        // Method
        if (!empty($url[1])) {
            if (method_exists($controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Params
        $this->params = !empty($url) ? array_values($url) : [];

        call_user_func_array([$controller, $this->method], $this->params);
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }

    private function notFound() {
        http_response_code(404);
        echo "<h1>404 - Page introuvable</h1>";
    }
}