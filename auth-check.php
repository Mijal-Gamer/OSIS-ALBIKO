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

// Check if user is authenticated via session or cookie token
$isValid = false;

// Priority 1: Check PHP session
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    $isValid = true;
}

// Priority 2: If no session, check token from COOKIE (works across Chrome profiles)
if (!$isValid && isset($_COOKIE['osis_token'])) {
    $token = $_COOKIE['osis_token'];
    $q = "SELECT * FROM users WHERE token='$token' LIMIT 1";
    $res = mysqli_query($conn_auth, $q);
    
    if ($res && mysqli_num_rows($res) === 1) {
        $user = mysqli_fetch_assoc($res);
        $_SESSION['login'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['token'] = $token;
        $isValid = true;
    }
}

if (!$isValid) {
    header("Location: login.php");
    exit;
}
?>
