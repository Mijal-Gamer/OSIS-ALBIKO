<?php
ini_set('session.cookie_secure', 0);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '');
ini_set('session.cookie_samesite', 'Lax');

session_name('OSIS_SESSION');
session_id();
session_start();
require 'connect-auth.php';

// Clear token from database if session exists
if (isset($_SESSION['token'])) {
    $token = $_SESSION['token'];
    $q = "UPDATE users SET token=NULL WHERE token='$token'";
    mysqli_query($conn_auth, $q);
}

// Clear cookies
setcookie('osis_token', '', time() - 3600, '/', '', false, true);
setcookie('osis_username', '', time() - 3600, '/', '', false, true);

// Hapus semua session
$_SESSION = array();

// Destroy session
session_destroy();

// Return JSON response if AJAX, else redirect
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'redirect' => 'login.php']);
    exit;
}

// Redirect ke login
header("Location: login.php");
exit;
?>
