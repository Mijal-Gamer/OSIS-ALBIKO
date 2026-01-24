<?php
// Helper Functions

// Database Query Helpers
function query($sql) {
    global $conn;
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        logError("Query Error: " . mysqli_error($conn));
        return false;
    }
    return $result;
}

function queryAuth($sql) {
    global $conn_auth;
    $result = mysqli_query($conn_auth, $sql);
    if (!$result) {
        logError("Query Error: " . mysqli_error($conn_auth));
        return false;
    }
    return $result;
}

function fetchAll($result) {
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

function fetchOne($result) {
    return mysqli_fetch_assoc($result);
}

// Security Functions
function sanitize($input) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($input));
}

function sanitizeAuth($input) {
    global $conn_auth;
    return mysqli_real_escape_string($conn_auth, trim($input));
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = generateToken();
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Validation Functions
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isValidURL($url) {
    return filter_var($url, FILTER_VALIDATE_URL);
}

function isValidUsername($username) {
    return preg_match('/^[a-zA-Z0-9_-]{3,20}$/', $username);
}

function isStrongPassword($password) {
    return strlen($password) >= 8 && 
           preg_match('/[A-Z]/', $password) && 
           preg_match('/[a-z]/', $password) && 
           preg_match('/[0-9]/', $password);
}

// File Upload Functions
function uploadFile($file, $destination = 'uploads/') {
    if (!isset($file['tmp_name'])) {
        return ['success' => false, 'message' => 'File tidak ditemukan'];
    }

    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return ['success' => false, 'message' => 'File terlalu besar'];
    }

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'message' => 'Tipe file tidak diizinkan'];
    }

    $filename = uniqid() . '_' . basename($file['name']);
    $filepath = __DIR__ . '/' . $destination . $filename;

    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename, 'path' => $destination . $filename];
    }

    return ['success' => false, 'message' => 'Gagal mengupload file'];
}

function deleteFile($filename) {
    $filepath = __DIR__ . '/uploads/' . $filename;
    if (file_exists($filepath)) {
        unlink($filepath);
        return true;
    }
    return false;
}

// Logging Functions
function logError($message) {
    if (!defined('LOG_FILE')) return;
    
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] ERROR: $message\n";
    
    if (!is_dir(dirname(LOG_FILE))) {
        mkdir(dirname(LOG_FILE), 0755, true);
    }
    
    file_put_contents(LOG_FILE, $log_message, FILE_APPEND);
}

function logActivity($user, $action) {
    $timestamp = date('Y-m-d H:i:s');
    $message = "[$timestamp] USER: $user | ACTION: $action\n";
    
    $activity_log = __DIR__ . '/logs/activity.log';
    if (!is_dir(dirname($activity_log))) {
        mkdir(dirname($activity_log), 0755, true);
    }
    
    file_put_contents($activity_log, $message, FILE_APPEND);
}

// Response Functions
function jsonResponse($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function redirectWithMessage($url, $message, $type = 'info') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    redirect($url);
}

// Flash Message Functions
function setMessage($message, $type = 'info') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

function getMessage() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = $_SESSION['message_type'] ?? 'info';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}

function displayMessage() {
    $msg = getMessage();
    if ($msg) {
        $class = 'alert-' . $msg['type'];
        echo "<div class='alert $class'>{$msg['message']}</div>";
    }
}

// Date/Time Functions
function formatDate($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

function formatTime($time, $format = 'H:i:s') {
    return date($format, strtotime($time));
}

function timeAgo($date) {
    $time = strtotime($date);
    $diff = time() - $time;
    
    if ($diff < 60) return "$diff detik lalu";
    $diff = floor($diff / 60);
    if ($diff < 60) return "$diff menit lalu";
    $diff = floor($diff / 3600);
    if ($diff < 24) return "$diff jam lalu";
    $diff = floor($diff / 86400);
    return "$diff hari lalu";
}

// Pagination Helper
function paginate($total_items, $items_per_page, $current_page) {
    $total_pages = ceil($total_items / $items_per_page);
    $current_page = max(1, min($current_page, $total_pages));
    $offset = ($current_page - 1) * $items_per_page;
    
    return [
        'offset' => $offset,
        'limit' => $items_per_page,
        'current_page' => $current_page,
        'total_pages' => $total_pages,
        'total_items' => $total_items
    ];
}

// Array/String Functions
function truncate($text, $limit = 100) {
    if (strlen($text) > $limit) {
        return substr($text, 0, $limit) . '...';
    }
    return $text;
}

function slug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

function randomString($length = 10) {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['login']) && $_SESSION['login'] === true;
}

// Get current user
function getCurrentUser() {
    return $_SESSION['username'] ?? null;
}

// Check admin status
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// JSON Request Check
function isAjax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}
?>