<?php
// Helper functions

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function url($path = '') {
    return APP_URL . '/' . ltrim($path, '/');
}

function redirect($path) {
    header('Location: ' . url($path));
    exit;
}

function back() {
    $ref = $_SERVER['HTTP_REFERER'] ?? url('');
    header('Location: ' . $ref);
    exit;
}

function flash($key, $value = null) {
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
    } else {
        $val = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $val;
    }
}

function hasFlash($key) {
    return isset($_SESSION['flash'][$key]);
}

function csrf_field() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

function csrf_check() {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        die('Token CSRF invalido');
    }
}

function money($amount) {
    return number_format((float)$amount, 2, ',', '.') . ' &euro;';
}

function moneyRaw($amount) {
    return number_format((float)$amount, 2, ',', '.');
}

function fecha($date, $format = 'd/m/Y') {
    if (empty($date)) return '';
    $d = DateTime::createFromFormat('Y-m-d', $date) ?: DateTime::createFromFormat('Y-m-d H:i:s', $date);
    return $d ? $d->format($format) : $date;
}

function fechaHora($date) {
    return fecha($date, 'd/m/Y H:i');
}

function currentUser() {
    return $_SESSION['user'] ?? null;
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function generarNumero($tipo) {
    $db = getDB();
    $campo_prefijo = 'prefijo_' . $tipo;
    $campo_num = 'siguiente_num_' . $tipo;

    $db->beginTransaction();
    try {
        $stmt = $db->query("SELECT $campo_prefijo, $campo_num FROM configuracion WHERE id = 1 FOR UPDATE");
        $conf = $stmt->fetch();

        $prefijo = $conf[$campo_prefijo];
        $num = $conf[$campo_num];
        $year = date('Y');
        $numero = $prefijo . '-' . $year . '-' . str_pad($num, 6, '0', STR_PAD_LEFT);

        $db->exec("UPDATE configuracion SET $campo_num = $campo_num + 1");
        $db->commit();

        return $numero;
    } catch (Exception $ex) {
        $db->rollBack();
        throw $ex;
    }
}

function getConfig() {
    static $config = null;
    if ($config === null) {
        $db = getDB();
        $config = $db->query("SELECT * FROM configuracion WHERE id = 1")->fetch();
    }
    return $config;
}

function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return trim(strip_tags($data));
}

function old($key, $default = '') {
    return $_SESSION['old_input'][$key] ?? $default;
}

function clearOldInput() {
    unset($_SESSION['old_input']);
}

function saveOldInput() {
    $_SESSION['old_input'] = $_POST;
}

function statusBadge($estado) {
    $colors = [
        'pendiente' => 'warning',
        'confirmada' => 'info',
        'en_curso' => 'primary',
        'completada' => 'success',
        'cancelada' => 'danger',
        'abierto' => 'warning',
        'en_proceso' => 'primary',
        'cerrado' => 'secondary',
        'borrador' => 'secondary',
        'enviado' => 'info',
        'enviada' => 'info',
        'aceptado' => 'success',
        'rechazado' => 'danger',
        'expirado' => 'dark',
        'entregado' => 'success',
        'facturado' => 'info',
        'pagada' => 'success',
        'vencida' => 'danger',
        'anulada' => 'dark',
        'planificacion' => 'secondary',
        'pausado' => 'warning',
        'completado' => 'success',
    ];
    $color = $colors[$estado] ?? 'secondary';
    return '<span class="badge bg-' . $color . '">' . ucfirst(e($estado)) . '</span>';
}
