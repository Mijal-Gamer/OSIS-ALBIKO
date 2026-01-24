<?php
require 'auth-check.php';

// Hitung jumlah admin dari database osis_auth
require 'connect-auth.php';

$adminCount = 0;
$result = mysqli_query($conn_auth, "SELECT COUNT(*) as total FROM users WHERE role = 'admin'");

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $adminCount = $row['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OSIS Astamayana</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
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
            color: white;
            min-height: 100vh;
        }

        .light {
            position: fixed;
            width: 600px;
            height: 600px;
            pointer-events: none;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 180, 255, 0.4), transparent 70%);
            filter: blur(80px);
            z-index: 0;
            animation: drift 15s ease-in-out infinite;
        }

        @keyframes drift {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(150px, -100px); }
        }

        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(0, 15, 30, 0.96);
            backdrop-filter: blur(12px);
            padding: 12px 30px;
            z-index: 100;
            border-bottom: 1px solid rgba(0, 200, 255, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.8s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-100%); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        header h2 {
            color: #00e0ff;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 0 2px 10px rgba(0, 200, 255, 0.3);
            transition: all 0.3s ease;
        }

        header h2:hover {
            color: #00ffff;
            text-shadow: 0 2px 15px rgba(0, 255, 255, 0.6);
        }

        header i {
            font-size: 2em;
            color: #00e0ff;
            transition: all 0.3s ease;
        }

        header i:hover {
            transform: scale(1.1) rotate(5deg);
            color: #00ffff;
        }

        .nav-links {
            display: flex;
            gap: 15px;
        }

        .nav-links a {
            color: #cceeff;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }

        .nav-links a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(0, 200, 255, 0.1);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .nav-links a:hover::before {
            left: 0;
        }

        .nav-links a:hover {
            color: #00ffff;
            transform: translateY(-2px);
        }

        .logout-btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b) !important;
            color: white !important;
            position: relative;
            overflow: hidden;
        }

        .logout-btn::before {
            background: rgba(255, 255, 255, 0.1) !important;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #e85e50, #d43f2f) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 120px 30px 50px;
        }

        h1 {
            color: #00e0ff;
            font-size: 2.5em;
            margin-bottom: 40px;
            text-align: center;
            text-shadow: 0 0 15px rgba(0, 200, 255, 0.3);
            animation: slideDown 0.8s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .dashboard-card {
            background: rgba(255, 255, 255, 0.06);
            border: 2px solid rgba(0, 255, 255, 0.25);
            border-radius: 15px;
            padding: 40px 30px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
            text-align: center;
            animation: slideUp 0.8s ease-out forwards;
            opacity: 0;
            position: relative;
            overflow: hidden;
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 200, 255, 0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 0;
        }

        .dashboard-card:hover::before {
            opacity: 1;
        }

        .dashboard-card:nth-child(1) { animation-delay: 0.1s; }
        .dashboard-card:nth-child(2) { animation-delay: 0.2s; }
        .dashboard-card:nth-child(3) { animation-delay: 0.3s; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dashboard-card:hover {
            transform: translateY(-15px) scale(1.05);
            box-shadow: 0 20px 60px rgba(0, 225, 255, 0.35);
            border-color: rgba(0, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.09);
        }

        .dashboard-card i {
            font-size: 3.5em;
            color: #00e0ff;
            margin-bottom: 15px;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            z-index: 1;
            display: inline-block;
        }

        .dashboard-card:hover i {
            color: #00ffff;
            transform: scale(1.3) rotate(10deg);
            filter: drop-shadow(0 0 15px rgba(0, 255, 255, 0.5));
        }

        .dashboard-card h3 {
            color: #00e0ff;
            font-size: 1.4em;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
            font-weight: 700;
        }

        .dashboard-card h3::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #00e0ff, #0077ff);
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }

        .dashboard-card:hover h3 {
            color: #00ffff;
        }

        .dashboard-card:hover h3::after {
            width: 60%;
        }

        .dashboard-card p {
            color: #b3eaff;
            font-size: 0.95em;
            line-height: 1.6;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .dashboard-card a {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 28px;
            background: linear-gradient(135deg, #00e0ff, #0077ff);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid rgba(0, 200, 255, 0.3);
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .dashboard-card a::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
            z-index: -1;
        }

        .dashboard-card a:hover::before {
            width: 300px;
            height: 300px;
        }

        .dashboard-card a:hover {
            background: linear-gradient(135deg, #00ffff, #00e0ff);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 200, 255, 0.4);
        }

        .welcome-section {
            background: rgba(255, 255, 255, 0.04);
            border: 2px solid rgba(0, 200, 255, 0.2);
            border-radius: 15px;
            padding: 50px 40px;
            text-align: center;
            margin-bottom: 50px;
            backdrop-filter: blur(10px);
            animation: fadeIn 1s ease-out;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(0, 200, 255, 0.1), transparent);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, -30px); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .welcome-section h2 {
            color: #00e0ff;
            font-size: 2em;
            margin-bottom: 20px;
            text-shadow: 0 0 15px rgba(0, 200, 255, 0.3);
            position: relative;
            z-index: 1;
            animation: slideDown 0.8s ease-out;
        }

        .welcome-section p {
            color: #b3eaff;
            font-size: 1.05em;
            line-height: 1.6;
            position: relative;
            z-index: 1;
            animation: slideUp 0.8s ease-out 0.1s both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .welcome-section .admin-count {
            margin-top: 20px;
            padding: 15px 20px;
            background: rgba(0, 200, 255, 0.1);
            border-left: 4px solid #00e0ff;
            border-radius: 8px;
            color: #00e0ff;
            font-weight: 600;
            position: relative;
            z-index: 1;
            animation: slideUp 0.8s ease-out 0.2s both;
        }

        .welcome-section .admin-count strong {
            color: #00ffff;
            font-size: 1.2em;
        }

        .welcome-section p {
            color: #b3eaff;
            font-size: 1.05em;
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .stat-box {
            background: rgba(0, 200, 255, 0.1);
            border: 2px solid rgba(0, 200, 255, 0.25);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-box:hover {
            border-color: rgba(0, 200, 255, 0.5);
            background: rgba(0, 200, 255, 0.15);
            transform: translateY(-5px);
        }

        .stat-box h3 {
            color: #00e0ff;
            font-size: 2em;
            margin-bottom: 8px;
        }

        .stat-box p {
            color: #9be8ff;
            font-size: 0.9em;
        }

        footer {
            text-align: center;
            padding: 40px 30px;
            color: #9be8ff;
            border-top: 1px solid rgba(0, 200, 255, 0.1);
            background: rgba(0, 15, 30, 0.3);
            transition: all 0.3s ease;
        }

        footer:hover {
            background: rgba(0, 15, 30, 0.5);
        }

        .particle {
            position: fixed;
            width: 3px;
            height: 3px;
            background: rgba(0, 255, 255, 0.6);
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
            animation: float 8s linear infinite;
            box-shadow: 0 0 8px rgba(0, 255, 255, 0.6);
        }

        @keyframes float {
            0% { transform: translateY(100vh) translateX(0) scale(1); opacity: 1; }
            100% { transform: translateY(-100vh) translateX(100px) scale(0); opacity: 0; }
        }

        @media (max-width: 768px) {
            .container {
                padding: 100px 15px 50px;
            }

            h1 {
                font-size: 1.8em;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .welcome-section {
                padding: 30px 20px;
            }

            .welcome-section h2 {
                font-size: 1.5em;
            }

            header {
                flex-wrap: wrap;
                gap: 10px;
            }

            .nav-links {
                gap: 10px;
            }

            .nav-links a {
                padding: 6px 10px;
                font-size: 0.9em;
            }
        }

        @media (max-width: 480px) {
            .nav-links {
                flex-direction: column;
            }

            .dashboard-card {
                padding: 25px 20px;
            }

            .dashboard-card i {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <div class="light"></div>
    <div class="light"></div>

    <header>
        <div class="header-left">
            <i class="ri-dashboard-line"></i>
            <h2>Admin Panel</h2>
        </div>
        <div class="nav-links">
            <a href="#"><i class="ri-home-line"></i> Dashboard</a>
            <a href="edit-konten.php"><i class="ri-edit-line"></i> Edit Konten</a>
            <a href="feedback.php"><i class="ri-chat-3-line"></i> Feedback</a>
            <a href="diagnostic.php"><i class="ri-stethoscope-line"></i> Diagnostic</a>
            <a href="logout.php" class="logout-btn"><i class="ri-logout-box-line"></i> Logout</a>
        </div>
    </header>

    <div class="container">
        <h1>Selamat Datang di Dashboard OSIS 👋</h1>

        <div class="welcome-section">
            <h2>Dashboard OSIS Astamayana</h2>
            <p>Kelola seluruh konten dan informasi OSIS dari satu tempat yang mudah digunakan</p>
            
            <div class="stats-section">
                <div class="stat-box">
                    <h3><?php echo $adminCount; ?></h3>
                    <p>Admin Aktif</p>
                </div>
                <div class="stat-box">
                    <h3 id="feedbackCount">0</h3>
                    <p>Feedback Diterima</p>
                </div>
                <div class="stat-box">
                    <h3 id="pageView">Aktif</h3>
                    <p>Status Website</p>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <i class="ri-edit-2-line"></i>
                <h3>Edit Konten</h3>
                <p>Kelola dan update konten halaman utama termasuk tentang, kegiatan, dan media sosial</p>
                <a href="edit-konten.php">Edit Sekarang →</a>
            </div>

            <div class="dashboard-card">
                <i class="ri-chat-3-line"></i>
                <h3>Lihat Feedback</h3>
                <p>Baca dan kelola feedback dari pengunjung website yang telah masuk</p>
                <a href="feedback.php">Lihat Feedback →</a>
            </div>

            <div class="dashboard-card">
                <i class="ri-home-line"></i>
                <h3>Kunjungi Website</h3>
                <p>Buka website publik untuk melihat tampilan website secara keseluruhan</p>
                <a href="index.php">Buka Website →</a>
            </div>
        </div>

        <footer>
            <p style="color: #00e0ff; font-weight: 600; margin-bottom: 10px;">© 2025 OSIS Astamayana - SMP AL ABIDIN Sukoharjo</p>
            <p>Sistem manajemen OSIS yang modern dan user-friendly</p>
        </footer>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.5.0/firebase-app.js";
        import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/12.5.0/firebase-database.js";

        const firebaseConfig = {
            apiKey: "AIzaSyDAvmSiSgfijLYb1_e8p1mf5rA8oaYpG1Y",
            authDomain: "osis-asstamayana.firebaseapp.com",
            databaseURL: "https://osis-asstamayana-default-rtdb.asia-southeast1.firebasedatabase.app",
            projectId: "osis-asstamayana",
            storageBucket: "osis-asstamayana.firebasestorage.app",
            messagingSenderId: "487901502731",
            appId: "1:487901502731:web:e0ed0778bb4c796bd2960e",
            measurementId: "G-TJ8W5XV0GH"
        };

        try {
            const app = initializeApp(firebaseConfig);
            const db = getDatabase(app);
            console.log("✅ Firebase initialized in dashboard!");

            const feedbackRef = ref(db, "feedback");
            onValue(feedbackRef, (snapshot) => {
                let feedbackCount = 0;
                if (snapshot.exists()) {
                    feedbackCount = Object.keys(snapshot.val()).length;
                }
                console.log("📊 Feedback count from Firebase:", feedbackCount);
                document.getElementById("feedbackCount").textContent = feedbackCount;
            });
        } catch (error) {
            console.error("❌ Firebase error:", error);
            document.getElementById("feedbackCount").textContent = "Error";
        }

        // Buat particles
        const particleCount = 20;
        for (let i = 0; i < particleCount; i++) {
            const p = document.createElement("div");
            p.classList.add("particle");
            p.style.left = Math.random() * 100 + "vw";
            p.style.top = Math.random() * 100 + "vh";
            p.style.animationDuration = (5 + Math.random() * 5) + "s";
            p.style.animationDelay = Math.random() * 2 + "s";
            document.body.appendChild(p);
        }

        console.log("✨ Dashboard loaded successfully!");
    </script>
</body>
</html>