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

$error = '';
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statement (aman dari SQL Injection)
    $stmt = mysqli_prepare($conn_auth, "SELECT * FROM users WHERE username=? AND password=? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res && mysqli_num_rows($res) === 1) {
        $user = mysqli_fetch_assoc($res);
        
        // Generate unique token
        $token = bin2hex(random_bytes(32));
        
        // Save token to database (gunakan prepared statement)
        $updateStmt = mysqli_prepare($conn_auth, "UPDATE users SET token=? WHERE id=?");
        mysqli_stmt_bind_param($updateStmt, "si", $token, $user['id']);
        mysqli_stmt_execute($updateStmt);
        
        // Set token to COOKIE (accessible across all Chrome profiles)
        setcookie('osis_token', $token, 0, '/', '', false, true);
        setcookie('osis_username', $user['username'], 0, '/', '', false, true);
        
        $_SESSION['login'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['token'] = $token;

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'redirect' => 'dashboard.php', 'token' => $token]);
            exit;
        } else {
            header("Location: dashboard.php");
            exit;
        }
    } else {
        $error = "Username atau password salah!";

        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => $error]);
            exit;
        }
    }
    
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Login OSIS Astamayana</title>
<link rel="icon" type="image/png" href="https://raw.githubusercontent.com/Mijal-Gamer/OSIS-ALBIKO/refs/heads/main/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: linear-gradient(135deg, #0f1f3f 0%, #08122a 50%, #0d1b2a 100%);
    color: white;
    min-height: 100vh;
    overflow: hidden;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-blur {
    position: fixed;
    width: 600px;
    height: 600px;
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
    filter: blur(80px);
    opacity: 0.6;
}

.blur-1 {
    background: radial-gradient(circle, rgba(0, 200, 255, 0.3), transparent);
    top: -200px;
    left: -200px;
    animation: float1 8s ease-in-out infinite;
}

.blur-2 {
    background: radial-gradient(circle, rgba(0, 150, 255, 0.2), transparent);
    bottom: -200px;
    right: -200px;
    animation: float2 10s ease-in-out infinite;
}

@keyframes float1 {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(50px, -50px); }
}

@keyframes float2 {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(-50px, 50px); }
}

.particles {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    pointer-events: none;
}

.particle {
    position: absolute;
    width: 2px;
    height: 2px;
    background: rgba(0, 200, 255, 0.5);
    border-radius: 50%;
    animation: float-up 8s ease-in infinite;
    box-shadow: 0 0 6px rgba(0, 200, 255, 0.4);
}

@keyframes float-up {
    0% {
        opacity: 0;
        transform: translateY(100vh) translateX(0);
    }
    50% {
        opacity: 1;
    }
    100% {
        opacity: 0;
        transform: translateY(-100vh) translateX(100px);
    }
}

.login-container {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 450px;
    padding: 20px;
}

.login-box {
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(0, 200, 255, 0.2);
    border-radius: 20px;
    padding: 50px;
    backdrop-filter: blur(10px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    animation: slideUp 0.8s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.login-box h2 {
    color: #00e0ff;
    font-size: 2em;
    margin-bottom: 10px;
    text-align: center;
    font-weight: 700;
}

.login-box .subtitle {
    text-align: center;
    color: #a0c4ff;
    margin-bottom: 35px;
    font-size: 0.95em;
    animation: fadeIn 0.8s ease 0.3s both;
}

.form-group {
    margin-bottom: 22px;
    animation: slideInForm 0.6s ease forwards;
    opacity: 0;
}

.form-group:nth-child(1) { animation-delay: 0.4s; }
.form-group:nth-child(2) { animation-delay: 0.5s; }

@keyframes slideInForm {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #a0c4ff;
    font-weight: 600;
    font-size: 0.95em;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.input-wrapper::before {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #00e0ff, #0077ff);
    transition: width 0.3s ease;
    z-index: 2;
}

.input-wrapper:focus-within::before {
    width: 100%;
}

.input-wrapper i {
    position: absolute;
    left: 15px;
    color: #00b8ff;
    font-size: 20px;
    z-index: 1;
    transition: all 0.3s ease;
}

.input-wrapper:focus-within i {
    color: #00e0ff;
    transform: scale(1.2);
}

.login-box input {
    width: 100%;
    padding: 14px 14px 14px 50px;
    border: 2px solid rgba(0, 200, 255, 0.2);
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
    font-size: 0.95em;
    transition: all 0.3s ease;
    outline: none;
    backdrop-filter: blur(10px);
}

.login-box input::placeholder {
    color: rgba(255, 255, 255, 0.4);
}

.login-box input:focus {
    border-color: rgba(0, 200, 255, 0.6);
    background: rgba(255, 255, 255, 0.1);
    box-shadow: 0 0 20px rgba(0, 200, 255, 0.3), inset 0 0 15px rgba(0, 200, 255, 0.05);
    transform: translateY(-2px);
}

.login-box button {
    width: 100%;
    padding: 14px;
    margin-top: 30px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #00e0ff, #0077ff);
    color: white;
    font-weight: 600;
    font-size: 1em;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(0, 200, 255, 0.2);
    position: relative;
    overflow: hidden;
    animation: slideInForm 0.6s ease 0.6s both;
}

.login-box button::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s ease, height 0.6s ease;
}

.login-box button:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(0, 200, 255, 0.4);
    background: linear-gradient(135deg, #00ffff, #0099ff);
}

.login-box button:hover::before {
    width: 300px;
    height: 300px;
}

.login-box button:active {
    transform: translateY(-1px);
}

.button-icon {
    margin-right: 8px;
    display: inline-block;
    transition: transform 0.3s ease;
}

.login-box button:hover .button-icon {
    transform: translateX(3px);
}

.error-message {
    margin-top: 15px;
    padding: 14px 16px;
    background: rgba(255, 59, 59, 0.12);
    border-left: 4px solid #ff6b6b;
    border-radius: 8px;
    color: #ffb3b3;
    font-weight: 500;
    font-size: 0.9em;
    text-align: left;
    animation: slideDown 0.3s ease, pulse 2s ease-in-out infinite;
    display: none;
    backdrop-filter: blur(10px);
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.error-message::before {
    content: '⚠️ ';
    margin-right: 8px;
}

.remember-section {
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid rgba(0, 200, 255, 0.1);
    animation: slideInForm 0.6s ease 0.7s both;
}

.remember-checkbox {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #a0c4ff;
    font-size: 0.95em;
    cursor: pointer;
    user-select: none;
    transition: all 0.3s ease;
}

.remember-checkbox input {
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: #00e0ff;
    transition: all 0.3s ease;
}

.remember-checkbox input:checked {
    box-shadow: 0 0 10px rgba(0, 224, 255, 0.5);
}

.remember-checkbox:hover {
    color: #00e0ff;
    transform: translateX(4px);
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.back-link {
    display: inline-block;
    text-align: center;
    margin-top: 20px;
    color: #00e0ff;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    animation: slideInForm 0.6s ease 0.8s both;
    position: relative;
    padding-bottom: 2px;
}

.back-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #00e0ff, #0077ff);
    transition: width 0.3s ease;
}

.back-link:hover::after {
    width: 100%;
}

.back-link:hover {
    color: #00ffff;
    transform: translateX(-3px);
}

.back-link i {
    margin-right: 6px;
    transition: transform 0.3s ease;
}

.back-link:hover i {
    transform: translateX(-3px);
}

.login-footer {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.85em;
    z-index: 2;
}

.login-footer p {
    margin: 4px 0;
    transition: all 0.3s ease;
}

.login-footer p:hover {
    color: rgba(0, 224, 255, 0.7);
}

@media (max-width: 768px) {
    .login-box {
        padding: 40px 30px;
        max-width: 90%;
    }

    .login-box h2 {
        font-size: 1.8em;
        margin-bottom: 15px;
    }

    .login-box p {
        font-size: 0.95em;
    }

    .form-group label {
        font-size: 0.9em;
    }

    .login-box input {
        padding: 12px 12px 12px 40px;
        font-size: 1em;
    }

    .login-box button {
        padding: 12px 20px;
        font-size: 0.95em;
    }
}

@media (max-width: 480px) {
    body {
        padding: 20px 10px;
    }

    .login-box {
        padding: 30px 20px;
        max-width: 100%;
    }

    .login-box h2 {
        font-size: 1.3em;
        margin-bottom: 12px;
    }

    .login-box p {
        font-size: 0.9em;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-size: 0.85em;
    }

    .login-box input {
        padding: 10px 10px 10px 35px;
        font-size: 14px;
        border-radius: 6px;
    }

    .login-box button {
        padding: 10px 15px;
        font-size: 0.9em;
        margin-top: 15px;
    }

    .login-footer {
        font-size: 0.85em;
    }

    .login-footer a {
        padding: 5px 10px;
    }
}

/* Floating animation for lock icon */
@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

/* Scale in animation */
@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
</head>
<body>
<div class="bg-blur blur-1"></div>
<div class="bg-blur blur-2"></div>

<div class="particles" id="particles"></div>

<div class="login-container">
    <div class="login-box">
        <div style="text-align: center; margin-bottom: 20px; animation: scaleIn 0.6s ease;">
            <i class="ri-shield-lock-line" style="font-size: 48px; color: #00e0ff; display: inline-block; animation: float 3s ease-in-out infinite;"></i>
        </div>
        <h2>🔐 Masuk OSIS</h2>
        <p class="subtitle">Kelola konten dengan aman dan mudah</p>

        <form method="POST">
            <div class="form-group">
                <label for="username"><i class="ri-user-3-line"></i> Username</label>
                <div class="input-wrapper">
                    <input 
                        name="username" 
                        id="username" 
                        type="text" 
                        placeholder="Masukkan username Anda"
                        autocomplete="off"
                        autofocus
                        required
                    >
                    <i class="ri-user-line"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password"><i class="ri-lock-2-line"></i> Password</label>
                <div class="input-wrapper">
                    <input 
                        name="password" 
                        id="password" 
                        type="password" 
                        placeholder="Masukkan password Anda"
                        autocomplete="off"
                        required
                    >
                    <i class="ri-lock-line"></i>
                </div>
            </div>

            <button type="submit">
                <i class="ri-login-box-line button-icon"></i>Masuk Sekarang
            </button>

            <div class="error-message" id="errorMessage"></div>

            <div class="remember-section">
                <label class="remember-checkbox">
                    <input type="checkbox" id="remember" name="remember">
                    <span><i class="ri-checkbox-circle-line"></i> Ingat saya di perangkat ini</span>
                </label>
            </div>

            <a class="back-link" href="index.php"><i class="ri-arrow-left-line"></i>Kembali ke Beranda</a>
        </form>
    </div>
</div>

<div class="login-footer">
    <p>🔒 © 2025 OSIS Astamayana - SMP AL ABIDIN Sukoharjo</p>
    <p>💙 Dibuat dengan ❤️ oleh Tim HUMAS</p>
</div>

<script>
    const particlesContainer = document.getElementById('particles');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const rememberCheckbox = document.getElementById('remember');
    const errorMessage = document.getElementById('errorMessage');
    const loginForm = document.querySelector('form');
    
    function createParticles() {
        for (let i = 0; i < 40; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.animationDuration = (5 + Math.random() * 5) + 's';
            particle.style.animationDelay = Math.random() * 2 + 's';
            particlesContainer.appendChild(particle);
        }
    }
    createParticles();

    // Load remembered username on page load
    window.addEventListener('load', function() {
        const savedUsername = localStorage.getItem('osis_remembered_username');
        if (savedUsername) {
            usernameInput.value = savedUsername;
            rememberCheckbox.checked = true;
        }
    });

    // Handle form submission with AJAX
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const username = usernameInput.value.trim();
        const password = passwordInput.value.trim();

        if (!username || !password) {
            showError('Username dan password wajib diisi!');
            return;
        }

        // Save username if checkbox is checked
        if (rememberCheckbox.checked) {
            localStorage.setItem('osis_remembered_username', username);
        } else {
            localStorage.removeItem('osis_remembered_username');
        }

        // Send login request via AJAX
        const formData = new FormData();
        formData.append('username', username);
        formData.append('password', password);

        fetch('login.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Save token to localStorage for cross-profile support
                localStorage.setItem('osis_token', data.token);
                localStorage.setItem('osis_username', username);
                // Redirect to dashboard on success
                window.location.href = data.redirect;
            } else {
                // Show error message and auto-hide after 10 seconds
                showError(data.error);
            }
        })
        .catch(error => {
            showError('Terjadi kesalahan saat login!');
            console.error('Error:', error);
        });
    });

    // Function to show error message and auto-hide after 10 seconds
    function showError(message) {
        errorMessage.textContent = '❌ ' + message;
        errorMessage.style.display = 'block';

        // Auto-hide after 10 seconds
        setTimeout(function() {
            errorMessage.style.display = 'none';
            errorMessage.textContent = '';
        }, 10000);
    }
</script>
</body>
</html>