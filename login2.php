<?php
require 'config.php';
require 'connect.php';
require 'connect-auth.php';
require 'helpers.php';

session_name('OSIS_SESSION');
session_start();

// Check if user is not logged in
if (isset($_SESSION['login'])) {
    redirect('dashboard.php');
}

// Handle login
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? sanitizeAuth($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi';
    } else {
        // Prepared statement
        $stmt = mysqli_prepare($conn_auth, "SELECT * FROM users WHERE username = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $user = fetchOne($result);
            
            // Verify password
            if (verifyPassword($password, $user['password']) || $password === $user['password']) {
                $_SESSION['login'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['is_admin'] = $user['role'] === 'admin';
                
                logActivity($username, 'Login');
                
                if (isAjax()) {
                    jsonResponse(true, 'Login berhasil', ['redirect' => 'dashboard.php']);
                } else {
                    redirect('dashboard.php');
                }
            } else {
                $error = 'Username atau password salah';
            }
        } else {
            $error = 'Username atau password salah';
        }

        mysqli_stmt_close($stmt);
    }

    if (isAjax() && $error) {
        jsonResponse(false, $error);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OSIS Astamayana</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: linear-gradient(135deg, #08122a, #020409, #0d1b2a);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .background-light {
            position: fixed;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(0, 180, 255, 0.3), transparent 70%);
            border-radius: 50%;
            filter: blur(80px);
            animation: drift 8s ease-in-out infinite;
            z-index: 0;
        }

        .background-light:first-child {
            top: -300px;
            left: -300px;
        }

        .background-light:last-child {
            bottom: -300px;
            right: -300px;
            animation-delay: 4s;
        }

        @keyframes drift {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(100px, -100px); }
        }

        .login-container {
            position: relative;
            z-index: 1;
            max-width: 450px;
            width: 90%;
            animation: slideInDown 0.8s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-box {
            background: rgba(0, 15, 30, 0.95);
            backdrop-filter: blur(15px);
            border: 2px solid rgba(0, 200, 255, 0.25);
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-section img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #00e0ff;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .logo-section img:hover {
            transform: scale(1.1);
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.5);
        }

        .logo-section h1 {
            color: #00e0ff;
            font-size: 1.8em;
            margin-bottom: 10px;
            text-shadow: 0 0 15px rgba(0, 200, 255, 0.3);
        }

        .logo-section p {
            color: #9be8ff;
            font-size: 0.95em;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #00e0ff;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 0.95em;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid rgba(0, 200, 255, 0.25);
            background: rgba(255, 255, 255, 0.05);
            color: white;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            font-size: 0.95em;
        }

        .form-group input::placeholder {
            color: rgba(224, 247, 255, 0.5);
        }

        .form-group input:focus {
            outline: none;
            border-color: rgba(0, 200, 255, 0.7);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 20px rgba(0, 200, 255, 0.2);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
            font-size: 0.9em;
        }

        .checkbox-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #9be8ff;
            cursor: pointer;
        }

        .checkbox-custom input {
            width: auto;
            margin: 0;
        }

        .forgot-password {
            color: #00e0ff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: #00ffff;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #00e0ff, #0077ff);
            border: 2px solid rgba(0, 200, 255, 0.3);
            color: white;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1em;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Poppins', sans-serif;
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #00ffff, #00e0ff);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 200, 255, 0.3);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .error-message {
            background: rgba(231, 76, 60, 0.1);
            border: 2px solid #e74c3c;
            color: #ff6b6b;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: none;
            animation: slideInDown 0.3s ease;
        }

        .error-message.show {
            display: block;
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            color: #9be8ff;
            font-size: 0.9em;
        }

        .register-link a {
            color: #00e0ff;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: #00ffff;
        }

        .particle {
            position: fixed;
            width: 2px;
            height: 2px;
            background: rgba(0, 255, 255, 0.4);
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
            animation: float 10s linear infinite;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
        }

        @keyframes float {
            0% { transform: translateY(100vh) translateX(0) scale(1); opacity: 1; }
            100% { transform: translateY(-100vh) translateX(200px) scale(0); opacity: 0; }
        }

        @media (max-width: 480px) {
            .login-box {
                padding: 30px 20px;
            }

            .logo-section h1 {
                font-size: 1.5em;
            }

            .form-group input {
                padding: 10px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="background-light"></div>
    <div class="background-light"></div>

    <div class="login-container">
        <div class="login-box">
            <div class="logo-section">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQAQDcLuO55zCJeSTV4kJHoTv1qHn8fQu8z7Q&s" alt="Logo OSIS">
                <h1>OSIS Admin</h1>
                <p>Portal Manajemen OSIS Astamayana</p>
            </div>

            <div class="error-message" id="errorMessage"></div>

            <form id="loginForm" method="POST" action="">
                <div class="form-group">
                    <label for="username"><i class="ri-user-line"></i> Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username Anda" required autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="password"><i class="ri-lock-line"></i> Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password Anda" required autocomplete="current-password">
                </div>

                <div class="form-options">
                    <label class="checkbox-custom">
                        <input type="checkbox" id="remember" name="remember">
                        <span>Ingat saya</span>
                    </label>
                    <a href="#" class="forgot-password">Lupa password?</a>
                </div>

                <button type="submit" class="login-btn">
                    <i class="ri-login-box-line"></i> Masuk
                </button>
            </form>

            <div class="register-link">
                Demo Account: <strong>admin</strong> / <strong>admin123</strong>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;
            const errorDiv = document.getElementById('errorMessage');

            if (!username || !password) {
                errorDiv.textContent = 'Username dan password harus diisi';
                errorDiv.classList.add('show');
                return;
            }

            // Save to localStorage if remember is checked
            if (remember) {
                localStorage.setItem('osis_username', username);
                localStorage.setItem('osis_remember', 'true');
            } else {
                localStorage.removeItem('osis_username');
                localStorage.removeItem('osis_remember');
            }

            // Submit form
            document.getElementById('loginForm').submit();
        });

        // Load saved username
        window.addEventListener('load', () => {
            if (localStorage.getItem('osis_remember') === 'true') {
                document.getElementById('username').value = localStorage.getItem('osis_username') || '';
                document.getElementById('remember').checked = true;
            }
        });

        // Create particles
        const particleCount = 30;
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + 'vw';
            particle.style.top = Math.random() * 100 + 'vh';
            particle.style.animationDuration = (8 + Math.random() * 4) + 's';
            particle.style.animationDelay = Math.random() * 2 + 's';
            document.body.appendChild(particle);
        }

        // Display error message if exists
        <?php if (!empty($error)): ?>
            document.getElementById('errorMessage').textContent = '<?php echo addslashes($error); ?>';
            document.getElementById('errorMessage').classList.add('show');
        <?php endif; ?>
    </script>
</body>
</html>