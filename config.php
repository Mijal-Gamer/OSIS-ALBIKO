<?php
// Configuration File - Centralized Settings
define('SITE_NAME', 'OSIS Astamayana');
define('SITE_URL', 'http://localhost/OSIS-ALBIKO/');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_MAIN', 'osis');
define('DB_AUTH', 'osis_auth');

// Security
define('HASH_ALGORITHM', 'sha256');
define('SESSION_TIMEOUT', 3600); // 1 hour
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_UPLOAD_SIZE', 5242880); // 5MB

// Firebase
define('FIREBASE_API_KEY', 'AIzaSyDAvmSiSgfijLYb1_e8p1mf5rA8oaYpG1Y');
define('FIREBASE_DB_URL', 'https://osis-asstamayana-default-rtdb.asia-southeast1.firebasedatabase.app');
define('FIREBASE_PROJECT_ID', 'osis-asstamayana');

// Email
define('ADMIN_EMAIL', 'osisalbisuk1@gmail.com');
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');

// Debug mode
define('DEBUG_MODE', true);
define('LOG_FILE', __DIR__ . '/logs/app.log');

// Create necessary directories
$dirs = [
    __DIR__ . '/uploads/',
    __DIR__ . '/logs/',
    __DIR__ . '/cache/',
    __DIR__ . '/backups/'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Error handling
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Set security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
?>