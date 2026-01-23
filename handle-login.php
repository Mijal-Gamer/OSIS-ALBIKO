<?php
session_start();
require 'connect-auth.php';

$username = $_POST['username'];
$password = $_POST['password'];

$q = mysqli_query($conn_auth, "SELECT * FROM users WHERE username='$username' LIMIT 1");
$user = mysqli_fetch_assoc($q);

if ($user && $password === $user['password']) {
    $_SESSION['login'] = true;
    $_SESSION['username'] = $user['username'];
    header("Location: dashboard.php");
    exit;
}

header("Location: login.php?error=1");
exit;
