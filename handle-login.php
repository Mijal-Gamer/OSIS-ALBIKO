<?php
require 'connect-auth.php';

session_name('OSIS_SESSION');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($username) || empty($password)) {
        $response = array('success' => false, 'message' => 'Username dan password harus diisi');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    $stmt = mysqli_prepare($conn_auth, "SELECT * FROM users WHERE username = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Try password_verify first
        if (function_exists('password_verify') && password_verify($password, $user['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $username;
            
            $response = array('success' => true, 'message' => 'Login berhasil', 'redirect' => 'dashboard.php');
            header('Content-Type: application/json');
            echo json_encode($response);
        } 
        // Fallback to plain text comparison
        elseif ($password === $user['password']) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $username;
            
            $response = array('success' => true, 'message' => 'Login berhasil', 'redirect' => 'dashboard.php');
            header('Content-Type: application/json');
            echo json_encode($response);
        }
        else {
            $response = array('success' => false, 'message' => 'Username atau password salah');
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    } else {
        $response = array('success' => false, 'message' => 'Username atau password salah');
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    mysqli_stmt_close($stmt);
} else {
    header("Location: login.php");
}
?>