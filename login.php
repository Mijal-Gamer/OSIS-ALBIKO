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
<link rel="stylesheet" href="assets/css/style.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: linear-gradient(135deg, #0a1428 0%, #0d2847 25%, #071e3a 50%, #0a0e27 75%, #0d1b2a 100%);
    color: white;
    min-height: 100vh;
    overflow: hidden;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

.bg-blur {
    position: fixed;
    width: 600px;
    height: 600px;
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
    filter: blur(100px);
    opacity: 0.7;
}

.blur-1 {
    background: radial-gradient(circle, rgba(0, 224, 255, 0.35), rgba(0, 150, 255, 0.1), transparent);
    top: -200px;
    left: -200px;
    animation: float1 12s ease-in-out infinite;
    box-shadow: 0 0 100px rgba(0, 200, 255, 0.3);
}

.blur-2 {
    background: radial-gradient(circle, rgba(0, 150, 255, 0.25), rgba(0, 100, 255, 0.1), transparent);
    bottom: -200px;
    right: -200px;
    animation: float2 15s ease-in-out infinite;
    box-shadow: 0 0 100px rgba(0, 150, 255, 0.2);
}

@keyframes float1 {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(80px, -80px) scale(1.1); }
}

@keyframes float2 {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(-80px, 80px) scale(1.1); }
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
    background: rgba(0, 200, 255, 0.7);
    border-radius: 50%;
    animation: float-up 10s ease-in infinite;
    box-shadow: 0 0 10px rgba(0, 200, 255, 0.6);
}

@keyframes float-up {
    0% {
        opacity: 0;
        transform: translateY(100vh) translateX(0) scale(0.5);
    }
    10% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        opacity: 0;
        transform: translateY(-100vh) translateX(150px) scale(0);
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
    background: rgba(13, 27, 42, 0.85);
    border: 2px solid rgba(0, 224, 255, 0.35);
    border-radius: 24px;
    padding: 60px;
    backdrop-filter: blur(20px);
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5),
                0 0 50px rgba(0, 200, 255, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
    animation: slideUp 1s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
    overflow: hidden;
}

.login-box::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(0, 224, 255, 0.1), transparent 70%);
    animation: rotate 20s linear infinite;
    z-index: 0;
    pointer-events: none;
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.login-box > * {
    position: relative;
    z-index: 1;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(80px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.login-box h2 {
    color: #00e0ff;
    font-size: 2.2em;
    margin-bottom: 8px;
    text-align: center;
    font-weight: 700;
    text-shadow: 0 0 20px rgba(0, 200, 255, 0.3);
    letter-spacing: -0.5px;
}
    margin-bottom: 10px;
    text-align: center;
    font-weight: 700;
}

.login-box .subtitle {
    text-align: center;
    color: #7fd8ff;
    margin-bottom: 40px;
    font-size: 1em;
    animation: fadeIn 1s ease 0.4s both;
    letter-spacing: 0.3px;
    font-weight: 500;
}

.form-group {
    margin-bottom: 26px;
    animation: slideInForm 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    opacity: 0;
}

.form-group:nth-child(1) { animation-delay: 0.5s; }
.form-group:nth-child(2) { animation-delay: 0.65s; }

@keyframes slideInForm {
    from {
        opacity: 0;
        transform: translateX(-30px) translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateX(0) translateY(0);
    }
}

.form-group label {
    display: block;
    margin-bottom: 10px;
    color: #7fd8ff;
    font-weight: 600;
    font-size: 0.95em;
    text-transform: uppercase;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    overflow: hidden;
    border-radius: 14px;
}

.input-wrapper::before {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 0;
    height: 3px;
    background: linear-gradient(90deg, #00e0ff, #00ffff, #0099ff);
    transition: width 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    z-index: 2;
}

.input-wrapper:focus-within::before {
    width: 100%;
}

.input-wrapper i {
    position: absolute;
    left: 16px;
    color: #00a8d8;
    font-size: 20px;
    z-index: 1;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.input-wrapper:focus-within i {
    color: #00ffff;
    transform: scale(1.3) rotate(8deg);
}

.login-box input {
    width: 100%;
    padding: 16px 16px 16px 55px;
    border: 2px solid rgba(0, 200, 255, 0.25);
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.04);
    color: #fff;
    font-size: 0.95em;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    outline: none;
    backdrop-filter: blur(15px);
    letter-spacing: 0.3px;
}

.login-box input::placeholder {
    color: rgba(255, 255, 255, 0.35);
}

.login-box input:focus {
    border-color: rgba(0, 224, 255, 0.7);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 30px rgba(0, 224, 255, 0.35),
                inset 0 0 20px rgba(0, 224, 255, 0.08),
                0 0 60px rgba(0, 150, 255, 0.2);
    transform: translateY(-4px);
}

.login-box button {
    width: 100%;
    padding: 16px;
    margin-top: 35px;
    border: none;
    border-radius: 14px;
    background: linear-gradient(135deg, #00e0ff 0%, #00b4ff 50%, #0077ff 100%);
    color: white;
    font-weight: 700;
    font-size: 1.05em;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 12px 35px rgba(0, 200, 255, 0.3),
                0 0 30px rgba(0, 150, 255, 0.2);
    position: relative;
    overflow: hidden;
    animation: slideInForm 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) 0.8s both;
    letter-spacing: 0.5px;
}

.login-box button::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    transform: translate(-50%, -50%);
    transition: width 0.7s cubic-bezier(0.34, 1.56, 0.64, 1),
                height 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.login-box button:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 18px 50px rgba(0, 224, 255, 0.5),
                0 0 50px rgba(0, 150, 255, 0.3),
                inset 0 0 30px rgba(255, 255, 255, 0.1);
    background: linear-gradient(135deg, #00ffff 0%, #00d0ff 50%, #0088ff 100%);
}

.login-box button:hover::before {
    width: 400px;
    height: 400px;
}

.login-box button:active {
    transform: translateY(-2px) scale(0.98);
}

.button-icon {
    margin-right: 10px;
    display: inline-block;
    transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.login-box button:hover .button-icon {
    transform: translateX(5px) scale(1.15);
}

.error-message {
    margin-top: 18px;
    padding: 16px 18px;
    background: rgba(255, 59, 59, 0.15);
    border-left: 5px solid #ff4757;
    border-radius: 10px;
    color: #ffb3b3;
    font-weight: 600;
    font-size: 0.92em;
    text-align: left;
    animation: slideDown 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), pulse 2.5s ease-in-out infinite;
    display: none;
    backdrop-filter: blur(10px);
    box-shadow: 0 0 20px rgba(255, 71, 87, 0.2);
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.85; }
}

.error-message::before {
    content: '⚠️ ';
    margin-right: 10px;
}

.remember-section {
    margin-top: 28px;
    padding-top: 28px;
    border-top: 2px solid rgba(0, 200, 255, 0.15);
    animation: slideInForm 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) 0.8s both;
}

.remember-checkbox {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #7fd8ff;
    font-size: 0.95em;
    cursor: pointer;
    user-select: none;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    font-weight: 500;
}

.remember-checkbox input {
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: #00e0ff;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    filter: drop-shadow(0 0 5px rgba(0, 224, 255, 0.3));
}

.remember-checkbox input:checked {
    box-shadow: 0 0 15px rgba(0, 224, 255, 0.6);
    transform: scale(1.1);
}

.remember-checkbox:hover {
    color: #00ffff;
    transform: translateX(6px);
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-15px);
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
    margin-top: 24px;
    color: #7fd8ff;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    animation: slideInForm 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) 0.9s both;
    position: relative;
    padding-bottom: 3px;
    font-size: 0.95em;
    letter-spacing: 0.3px;
}

.back-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #00e0ff, #00ffff, #0099ff);
    transition: width 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.back-link:hover::after {
    width: 100%;
}

.back-link:hover {
    color: #00ffff;
    transform: translateX(-5px);
    text-shadow: 0 0 15px rgba(0, 224, 255, 0.4);
}

.back-link i {
    margin-right: 8px;
    transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: inline-block;
}

.back-link:hover i {
    transform: translateX(-5px);
}

.login-footer {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    color: rgba(255, 255, 255, 0.55);
    font-size: 0.85em;
    z-index: 2;
}

.login-footer p {
    margin: 5px 0;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    font-weight: 500;
}

.login-footer p:hover {
    color: rgba(0, 224, 255, 0.8);
    text-shadow: 0 0 10px rgba(0, 224, 255, 0.3);
}

@media (max-width: 768px) {
    .login-box {
        padding: 45px 35px;
        max-width: 90%;
    }

    .login-box h2 {
        font-size: 1.9em;
        margin-bottom: 12px;
    }

    .login-box p {
        font-size: 0.96em;
    }

    .form-group label {
        font-size: 0.9em;
    }

    .login-box input {
        padding: 14px 14px 14px 48px;
        font-size: 1em;
    }

    .login-box button {
        padding: 14px 20px;
        font-size: 0.98em;
        margin-top: 28px;
    }
}

@media (max-width: 480px) {
    body {
        padding: 20px 10px;
    }

    .login-box {
        padding: 35px 22px;
        max-width: 100%;
    }

    .login-box h2 {
        font-size: 1.5em;
        margin-bottom: 10px;
    }

    .login-box p {
        font-size: 0.92em;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        font-size: 0.85em;
    }

    .login-box input {
        padding: 12px 12px 12px 40px;
        font-size: 14px;
        border-radius: 10px;
    }

    .login-box button {
        padding: 12px 15px;
        font-size: 0.95em;
        margin-top: 20px;
    }

    .login-footer {
        font-size: 0.8em;
    }

    .login-footer a {
        padding: 5px 10px;
    }
}

/* Floating animation for lock icon */
@keyframes float {
    0%, 100% {
        transform: translateY(0px) scale(1);
    }
    50% {
        transform: translateY(-15px) scale(1.05);
    }
}

/* Scale in animation */
@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.5) rotate(-15deg);
    }
    to {
        opacity: 1;
        transform: scale(1) rotate(0deg);
    }
}

/* Loading state */
.login-box button.loading {
    pointer-events: none;
    opacity: 0.8;
}

.login-box button.loading .button-icon {
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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
        const button = loginForm.querySelector('button');

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

        // Add loading state
        button.classList.add('loading');
        button.disabled = true;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="ri-loader-4-line button-icon"></i>Memproses...';

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
                
                // Success animation
                button.innerHTML = '<i class="ri-check-line button-icon"></i>Masuk Berhasil!';
                button.style.background = 'linear-gradient(135deg, #2ecc71, #27ae60)';
                button.style.boxShadow = '0 12px 35px rgba(46, 204, 113, 0.3)';
                
                // Redirect after brief delay
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 800);
            } else {
                // Reset button
                button.classList.remove('loading');
                button.disabled = false;
                button.innerHTML = originalText;
                
                // Show error message and auto-hide after 10 seconds
                showError(data.error);
            }
        })
        .catch(error => {
            // Reset button
            button.classList.remove('loading');
            button.disabled = false;
            button.innerHTML = originalText;
            
            showError('Terjadi kesalahan saat login!');
            console.error('Error:', error);
        });
    });

    // Function to show error message and auto-hide after 10 seconds
    function showError(message) {
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
        errorMessage.style.animation = 'none';
        
        // Trigger reflow
        void errorMessage.offsetWidth;
        
        errorMessage.style.animation = 'slideDown 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';

        // Auto-hide after 10 seconds
        setTimeout(function() {
            errorMessage.style.opacity = '0';
            errorMessage.style.transform = 'translateY(-15px)';
            
            setTimeout(() => {
                errorMessage.style.display = 'none';
                errorMessage.textContent = '';
                errorMessage.style.opacity = '1';
                errorMessage.style.transform = 'translateY(0)';
            }, 300);
        }, 10000);
    }
</script>
</body>
</html>