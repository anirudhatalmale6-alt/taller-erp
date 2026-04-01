<?php
// Application configuration
define('APP_NAME', 'Taller ERP');
define('APP_VERSION', '1.0.0');
define('APP_URL', '');
define('APP_TIMEZONE', 'Europe/Madrid');
define('APP_LOCALE', 'es_ES');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('UPLOAD_MAX_SIZE', 10 * 1024 * 1024); // 10MB

date_default_timezone_set(APP_TIMEZONE);
setlocale(LC_ALL, 'es_ES.UTF-8', 'es_ES', 'spanish');

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
