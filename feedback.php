<?php 
require 'auth-check.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback | OSIS Astamayana</title>
  <link rel="icon" type="image/png" href="https://raw.githubusercontent.com/Mijal-Gamer/OSIS-ALBIKO/refs/heads/main/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: radial-gradient(circle at center, #08122a, #020409);
      color: white;
      overflow-x: hidden;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 0.6s ease, transform 0.6s ease;
      position: relative;
    }

    body.loaded {
      opacity: 1;
      transform: translateY(0);
    }

    /* Partikel Latar */
    .particles {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
      pointer-events: none;
    }
    .particle {
      position: absolute;
      width: 4px;
      height: 4px;
      background: rgba(0, 200, 255, 0.6);
      border-radius: 50%;
      animation: twinkle 3s ease-in-out infinite;
    }
    @keyframes twinkle {
      0%, 100% { opacity: 0; transform: scale(0); }
      50% { opacity: 1; transform: scale(1); }
    }

    /* Header */
    header {
      width: 100%;
      padding: 20px;
      text-align: center;
      background: rgba(0, 255, 255, 0.05);
      border-bottom: 1px solid rgba(0, 255, 255, 0.2);
      box-shadow: 0 0 10px rgba(0, 255, 255, 0.2);
      position: sticky;
      top: 0;
      backdrop-filter: blur(10px);
      z-index: 10;
    }
    header img {
      width: 60px;
      border-radius: 50%;
      filter: drop-shadow(0 0 15px #00e0ff);
      animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }
    header h1 {
      color: #00e0ff;
      font-size: 24px;
      margin-top: 10px;
      text-shadow: 0 0 10px #00ffff;
      animation: glow 2s ease-in-out infinite alternate;
    }
    @keyframes glow {
      from { text-shadow: 0 0 10px rgba(0, 200, 255, 0.6); }
      to { text-shadow: 0 0 20px rgba(0, 255, 255, 0.9), 0 0 30px rgba(0, 255, 255, 0.4); }
    }

    /* Feedback container */
    .feedback-container {
      max-width: 800px;
      margin: 60px auto 40px;
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(0,255,255,0.2);
      border-radius: 20px;
      padding: 25px;
      box-shadow: 0 0 30px rgba(0,255,255,0.15);
      position: relative;
      z-index: 2;
      animation: fadeInUp 1s ease forwards;
      opacity: 0;
      transform: translateY(30px);
    }
    @keyframes fadeInUp {
      to { opacity: 1; transform: translateY(0); }
    }

    .feedback-item {
      background: rgba(255,255,255,0.05);
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 15px;
      border-left: 4px solid #00e0ff;
      transition: all 0.3s ease;
    }
    .feedback-item:hover {
      background: rgba(0,255,255,0.1);
      transform: translateX(6px);
      box-shadow: 0 0 15px rgba(0,255,255,0.3);
    }
    .feedback-text {
      font-size: 15px;
      color: #d0f7ff;
    }
    .feedback-time {
      text-align: right;
      font-size: 12px;
      color: rgba(255,255,255,0.5);
      margin-top: 5px;
    }

    /* Tombol kembali */
    .back-btn {
      display: block;
      margin: 30px auto 40px;
      text-align: center;
      background: linear-gradient(90deg, #00e0ff, #00ffff);
      color: #000;
      padding: 12px 25px;
      border-radius: 12px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s;
      width: fit-content;
      box-shadow: 0 0 20px rgba(0,255,255,0.2);
      position: relative;
      z-index: 2;
    }
    .back-btn:hover {
      box-shadow: 0 0 25px #00ffff, 0 0 40px #00e0ff;
      transform: scale(1.05);
    }

    /* Efek cahaya */
    .light {
      position: absolute;
      top: 0;
      left: 0;
      width: 600px;
      height: 600px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(0, 180, 255, 0.6), transparent 70%);
      filter: blur(120px);
      pointer-events: none;
      z-index: 0;
      animation: pulse 4s ease-in-out infinite;
    }
    @keyframes pulse {
      0%,100% { transform: translate(-300px, -300px) scale(1); }
      50% { transform: translate(-300px, -300px) scale(1.1); }
    }

    footer {
      margin-top: auto;
      padding: 20px;
      text-align: center;
      color: rgba(255,255,255,0.6);
      font-size: 14px;
      z-index: 2;
    }
  </style>
</head>
<body>
  <div class="particles" id="particles"></div>
  <div class="light"></div>

  <header>
    <img src="https://raw.githubusercontent.com/Mijal-Gamer/OSIS-ALBIKO/refs/heads/main/favicon.ico" alt="Logo OSIS">
    <h1>📨 Daftar Feedback Pengunjung</h1>
  </header>

  <div class="feedback-container" id="feedbackList">
    <p>⏳ Memuat data feedback...</p>
  </div>

  <a href="dashboard.php" class="back-btn"><i class="ri-arrow-left-line"></i> Kembali ke Dashboard</a>

  <footer>
    © 2025 OSIS Astamayana — Panel Admin
  </footer>

  <script>
    // 🧱 Cek login
    const ok = localStorage.getItem('osis_admin_auth');
    if (ok !== 'true') {
      window.location.href = "login.php";
    }
    
    // Efek partikel latar
    function createParticles() {
      const particlesContainer = document.getElementById('particles');
      for (let i = 0; i < 50; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 3 + 's';
        particlesContainer.appendChild(particle);
      }
    }
    window.addEventListener('load', () => {
      document.body.classList.add('loaded');
      createParticles();
    });

    // Efek cahaya mengikuti mouse
    const light = document.querySelector('.light');
    let mouseX = 0, mouseY = 0, lightX = 0, lightY = 0;
    document.addEventListener('mousemove', e => { mouseX = e.clientX; mouseY = e.clientY; });
    function animateLight() {
      lightX += (mouseX - lightX) * 0.1;
      lightY += (mouseY - lightY) * 0.1;
      light.style.transform = `translate(${lightX - light.offsetWidth / 2}px, ${lightY - light.offsetHeight / 2}px)`;
      requestAnimationFrame(animateLight);
    }
    requestAnimationFrame(animateLight);
  </script>

  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/12.4.0/firebase-app.js";
    import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/12.4.0/firebase-database.js";

    const firebaseConfig = {
      apiKey: "AIzaSyDAvmSiSgfijLYb1_e8p1mf5rA8oaYpG1Y",
      authDomain: "osis-asstamayana.firebaseapp.com",
      databaseURL: "https://osis-asstamayana-default-rtdb.asia-southeast1.firebasedatabase.app",
      projectId: "osis-asstamayana",
      storageBucket: "osis-asstamayana.appspot.com",
      messagingSenderId: "487901502731",
      appId: "1:487901502731:web:e0ed0778bb4c796bd2960e",
      measurementId: "G-TJ8W5XV0GH"
    };

    const app = initializeApp(firebaseConfig);
    const db = getDatabase(app);
    const feedbackList = document.getElementById('feedbackList');
    const feedbackRef = ref(db, 'feedback');

    onValue(feedbackRef, (snapshot) => {
      const data = snapshot.val();
      feedbackList.innerHTML = "";
      if (data) {
        const entries = Object.entries(data).reverse();
        entries.forEach(([id, item]) => {
          feedbackList.innerHTML += `
            <div class="feedback-item">
              <div class="feedback-text">💬 ${item.text}</div>
              <div class="feedback-time">${item.time || "Waktu tidak diketahui"}</div>
            </div>
          `;
        });
      } else {
        feedbackList.innerHTML = "<p>🚫 Belum ada feedback yang dikirim.</p>";
      }
    });
  </script>
</body>
</html>
