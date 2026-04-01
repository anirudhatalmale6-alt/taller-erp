<?php
// Simple Router

class Router {

    public function dispatch() {
        $controller = $_GET['c'] ?? 'dashboard';
        $action = $_GET['a'] ?? 'index';

        // Sanitize
        $controller = preg_replace('/[^a-zA-Z0-9_]/', '', $controller);
        $action = preg_replace('/[^a-zA-Z0-9_]/', '', $action);

        // Map controller name to class
        $controllerClass = ucfirst($controller) . 'Controller';
        $controllerFile = __DIR__ . '/../controllers/' . $controllerClass . '.php';

        if (!file_exists($controllerFile)) {
            http_response_code(404);
            die('Pagina no encontrada');
        }

        require_once $controllerFile;

        if (!class_exists($controllerClass)) {
            http_response_code(404);
            die('Controlador no encontrado');
        }

        $instance = new $controllerClass();

        if (!method_exists($instance, $action)) {
            http_response_code(404);
            die('Accion no encontrada');
        }

        $instance->$action();
    }
}
