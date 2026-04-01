<?php
// Base Controller class

class Controller {

    protected function view($name, $data = []) {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $name . '.php';
        if (!file_exists($viewFile)) {
            die("Vista no encontrada: $name");
        }
        // Capture view content
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // Include layout
        include __DIR__ . '/../views/layout/master.php';
    }

    protected function viewRaw($name, $data = []) {
        extract($data);
        include __DIR__ . '/../views/' . $name . '.php';
    }

    protected function json($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function requireAuth() {
        if (!isLoggedIn()) {
            redirect('index.php?c=auth&a=login');
        }
    }

    protected function requireRole($roles) {
        $this->requireAuth();
        $user = currentUser();
        if (!in_array($user['rol'], (array)$roles)) {
            die('No tienes permisos para esta accion');
        }
    }

    protected function getParam($key, $default = '') {
        return sanitize($_GET[$key] ?? $_POST[$key] ?? $default);
    }

    protected function postParam($key, $default = '') {
        return sanitize($_POST[$key] ?? $default);
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}
