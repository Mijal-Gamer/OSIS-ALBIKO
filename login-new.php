<?php
session_name('OSIS_SESSION');
session_start();

// SEMUA ORANG HARUS LOGIN MANUAL - NO AUTO REDIRECT
// Tidak ada auto-redirect ke dashboard
// User harus mengisi form login setiap kali

require 'connect-auth.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi!";
    } else {
        // Query ke database osis_auth
        $stmt = mysqli_prepare($conn_auth, "SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1");
        
        if (!$stmt) {
            $error = "Error pada database: " . mysqli_error($conn_auth);
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                
                // Cek password (plain text atau hash)
                $passwordMatch = false;
                
                // Try password_verify dulu (jika password di-hash)
                if (password_verify($password, $user['password'])) {
                    $passwordMatch = true;
                }
                // Fallback ke plain text comparison
                elseif ($password === $user['password']) {
                    $passwordMatch = true;
                }

                if ($passwordMatch) {
                    // Login berhasil
                    $_SESSION['login'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'] ?? 'user';
                    $_SESSION['login_time'] = time();

                    // Redirect ke dashboard
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "⚠️ Password salah!";
                }
            } else {
                $error = "⚠️ Username tidak ditemukan!";
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OSIS Astamayana</title>
    <link rel="icon" type="image/png" href="https://raw.githubusercontent.com/Mijal-Gamer/OSIS-ALBIKO/refs/heads/main/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

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
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Floating background elements */
        .bg-light {
            position: fixed;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            filter: blur(80px);
            background: radial-gradient(circle, rgba(0, 180, 255, 0.3), transparent 70%);
        }

        .bg-light:nth-child(1) {
            top: -150px;
            left: -150px;
            animation: drift 20s ease-in-out infinite;
        }

        .bg-light:nth-child(2) {
            bottom: -150px;
            right: -150px;
            animation: drift 25s ease-in-out infinite reverse;
        }

        @keyframes drift {
            0%, 100% {
                transform: translate(0, 0);
            }
            50% {
                transform: translate(100px, 100px);
            }
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 450px;
            padding: 30px;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-box {
            background: rgba(8, 18, 42, 0.7);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(0, 200, 255, 0.2);
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 40px rgba(0, 200, 255, 0.1);
            transition: all 0.3s ease;
        }

        .login-box:hover {
            border-color: rgba(0, 200, 255, 0.4);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.6), 0 0 50px rgba(0, 200, 255, 0.15);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 40px;
            animation: scaleIn 0.6s ease-out;
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .logo-section img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 3px solid rgba(0, 200, 255, 0.5);
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .logo-section img:hover {
            transform: scale(1.1) rotate(5deg);
            border-color: rgba(0, 200, 255, 0.8);
            box-shadow: 0 0 30px rgba(0, 200, 255, 0.4);
        }

        .logo-section h1 {
            font-size: 1.8em;
            color: #00e0ff;
            margin-bottom: 8px;
            font-weight: 700;
            text-shadow: 0 0 20px rgba(0, 200, 255, 0.3);
        }

        .logo-section p {
            color: #9be8ff;
            font-size: 0.9em;
            letter-spacing: 1px;
        }

        .form-group {
            margin-bottom: 25px;
            animation: slideInLeft 0.6s ease-out;
            animation-fill-mode: both;
        }

        .form-group:nth-child(1) {
            animation-delay: 0.1s;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.2s;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #00e0ff;
            font-weight: 600;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .form-group input:focus + label,
        .form-group input:not(:placeholder-shown) + label {
            color: #00ffff;
            text-shadow: 0 0 10px rgba(0, 200, 255, 0.5);
        }

        .form-group input {
            width: 100%;
            padding: 14px 18px;
            background: rgba(0, 50, 100, 0.2);
            border: 2px solid rgba(0, 200, 255, 0.25);
            border-radius: 12px;
            color: white;
            font-size: 1em;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            order: -1; /* Letakkan input di atas label */
        }

        .form-group input::placeholder {
            color: rgba(0, 200, 255, 0.4);
        }

        .form-group input:focus {
            outline: none;
            background: rgba(0, 100, 150, 0.3);
            border-color: rgba(0, 200, 255, 0.6);
            box-shadow: 0 0 20px rgba(0, 200, 255, 0.2), inset 0 0 10px rgba(0, 200, 255, 0.05);
            transform: translateY(-2px);
        }

        .error-message {
            background: rgba(231, 76, 60, 0.15);
            border: 2px solid rgba(231, 76, 60, 0.5);
            color: #ff6b6b;
            padding: 15px 18px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: none;
            animation: slideInDown 0.4s ease-out;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-message.show {
            display: flex;
        }

        .error-message i {
            font-size: 1.2em;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-btn {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, #00e0ff, #0077ff);
            border: 2px solid rgba(0, 200, 255, 0.4);
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 1em;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-transform: uppercase;
            letter-spacing: 1px;
            animation: slideInLeft 0.6s ease-out 0.3s backwards;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #00ffff, #00d4ff);
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(0, 200, 255, 0.3);
            border-color: rgba(0, 200, 255, 0.7);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .login-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .login-btn i {
            font-size: 1.1em;
        }

        .footer-text {
            text-align: center;
            margin-top: 30px;
            color: #7f8c8d;
            font-size: 0.85em;
            animation: fadeIn 0.8s ease-out 0.6s backwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .footer-text a {
            color: #00e0ff;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .footer-text a:hover {
            color: #00ffff;
            text-shadow: 0 0 10px rgba(0, 200, 255, 0.5);
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 15px;
            }

            .login-box {
                padding: 30px 20px;
            }

            .logo-section h1 {
                font-size: 1.4em;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-group input {
                padding: 12px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-light"></div>
    <div class="bg-light"></div>

    <div class="login-container">
        <div class="login-box">
            <div class="logo-section">
                <img src="https://raw.githubusercontent.com/Mijal-Gamer/OSIS-ALBIKO/refs/heads/main/favicon.ico" alt="Logo OSIS">
                <h1><i class="ri-shield-check-line"></i> Login</h1>
                <p>OSIS Astamayana</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message show">
                    <i class="ri-error-warning-line"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Masukkan username"
                        required 
                        autofocus
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                    >
                    <label for="username"><i class="ri-user-line"></i> Username</label>
                </div>

                <div class="form-group">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Masukkan password"
                        required
                    >
                    <label for="password"><i class="ri-lock-line"></i> Password</label>
                </div>

                <button type="submit" class="login-btn">
                    <i class="ri-login-circle-line"></i> Masuk Sekarang
                </button>
            </form>

            <div class="footer-text">
                <p>© 2026 OSIS Astamayana. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
