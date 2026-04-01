<?php
// Taller ERP - Entry Point

error_reporting(E_ALL);
ini_set('display_errors', 0);

// Load config
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

// Load core
require_once __DIR__ . '/core/Helpers.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Controller.php';

// Load all models
foreach (glob(__DIR__ . '/models/*.php') as $modelFile) {
    require_once $modelFile;
}

// Route
require_once __DIR__ . '/core/Router.php';
$router = new Router();
$router->dispatch();
