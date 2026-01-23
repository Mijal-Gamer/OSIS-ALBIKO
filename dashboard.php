<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin | OSIS Astamayana</title>
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
      min-height: 100vh;
      overflow-x: hidden;
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

    /* Animated background particles */
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
      0%, 100% {
        opacity: 0;
        transform: scale(0);
      }
      50% {
        opacity: 1;
        transform: scale(1);
      }
    }

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
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
    }

    header h1 {
      color: #00e0ff;
      font-size: 24px;
      margin-top: 10px;
      text-shadow: 0 0 10px #00ffff;
      animation: glow 2s ease-in-out infinite alternate;
    }

    @keyframes glow {
      from {
        text-shadow: 0 0 10px rgba(0, 200, 255, 0.6);
      }
      to {
        text-shadow: 0 0 20px rgba(0, 200, 255, 0.8), 0 0 30px rgba(0, 200, 255, 0.4);
      }
    }

    .menu-container {
      margin-top: 60px;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      padding-bottom: 40px;
    }

    .menu-card {
      width: 260px;
      height: 160px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(0, 255, 255, 0.2);
      border-radius: 20px;
      text-align: center;
      padding: 20px;
      cursor: pointer;
      transition: all 0.4s ease;
      box-shadow: 0 0 20px rgba(0, 255, 255, 0.15);
      position: relative;
      overflow: hidden;
      opacity: 0;
      transform: translateY(30px);
      animation: slideIn 0.6s ease forwards;
    }

    .menu-card:nth-child(1) {
      animation-delay: 0.2s;
    }

    .menu-card:nth-child(2) {
      animation-delay: 0.4s;
    }

    .menu-card:nth-child(3) {
      animation-delay: 0.6s;
    }

    @keyframes slideIn {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .menu-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(0, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    .menu-card:hover::before {
      left: 100%;
    }

    .menu-card:hover {
      transform: translateY(-10px) scale(1.05);
      box-shadow: 0 0 30px #00ffff;
      border-color: #00ffff;
    }

    .menu-card h2 {
      color: #00e0ff;
      margin-top: 15px;
      font-size: 20px;
      transition: color 0.3s;
    }

    .menu-card:hover h2 {
      color: #00ffff;
    }

    .menu-card i {
      font-size: 40px;
      color: #00ffff;
      filter: drop-shadow(0 0 10px #00ffff);
      transition: transform 0.3s;
    }

    .menu-card:hover i {
      transform: scale(1.2);
    }

    .logout {
      margin-top: 50px;
      background: #ff4b4b;
      border: none;
      padding: 12px 25px;
      border-radius: 10px;
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 600;
      position: relative;
      overflow: hidden;
    }

    .logout::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.5s;
    }

    .logout:hover::before {
      left: 100%;
    }

    .logout:hover {
      background: #ff6666;
      box-shadow: 0 0 15px #ff5c5c;
      transform: scale(1.05);
    }

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
      0%, 100% {
        transform: translate(-300px, -300px) scale(1);
      }
      50% {
        transform: translate(-300px, -300px) scale(1.1);
      }
    }

    footer {
      margin-top: auto;
      padding: 15px;
      text-align: center;
      color: rgba(255, 255, 255, 0.6);
      font-size: 14px;
      animation: fadeInUp 1s ease forwards;
      opacity: 0;
      transform: translateY(20px);
    }

    @keyframes fadeInUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 768px) {
      .menu-container {
        gap: 20px;
      }
      .menu-card {
        width: 200px;
        height: 140px;
      }
      header h1 {
        font-size: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="particles" id="particles"></div>
  <div class="light"></div>

  <header>
    <img src="https://raw.githubusercontent.com/Mijal-Gamer/OSIS-ALBIKO/refs/heads/main/favicon.ico" alt="Logo OSIS">
    <h1>Dashboard Admin OSIS Astamayana</h1>
  </header>

    <div class="menu-container">
    <!-- 📝 Menu baru: Edit Konten -->
    <div class="menu-card" onclick="navigateTo('edit-konten')">
      <i class="ri-edit-2-line"></i>
      <h2>Edit Konten</h2>
    </div>

    <!-- 🖼️ Kelola Galeri -->
    <div class="menu-card" onclick="navigateTo('kelola-galeri')">
      <i class="ri-image-2-line"></i>
      <h2>Kelola Galeri</h2>
    </div>

    <!-- 📅 Jadwal Kegiatan -->
    <div class="menu-card" onclick="navigateTo('jadwal-kegiatan')">
      <i class="ri-calendar-event-line"></i>
      <h2>Jadwal Kegiatan</h2>
    </div>

    <!-- 💬 Lihat Feedback -->
    <div class="menu-card" onclick="navigateTo('feedback')">
      <i class="ri-chat-1-line"></i>
      <h2>Lihat Feedback</h2>
    </div>
  </div>


  <button class="logout" onclick="logout()">Logout</button>

  <footer>
    © 2025 OSIS Astamayana — Panel Admin
  </footer>

  <!-- ✨ Animasi fade-in dan particles -->
  <script>
    
    // 🧱 Cek login
    const ok = localStorage.getItem('osis_admin_auth');
    if (ok !== 'true') {
      window.location.href = "admin.php";
    }

    // 🌈 Efek cahaya smooth ngikutin mouse
    const light = document.querySelector('.light');
    let mouseX = 0, mouseY = 0;
    let lightX = 0, lightY = 0;
    document.addEventListener('mousemove', e => {
      mouseX = e.clientX;
      mouseY = e.clientY;
    });
    function animateLight() {
      lightX += (mouseX - lightX) * 0.1;
      lightY += (mouseY - lightY) * 0.1;
      light.style.transform = `translate(${lightX - light.offsetWidth / 2}px, ${lightY - light.offsetHeight / 2}px)`;
      requestAnimationFrame(animateLight);
    }
    requestAnimationFrame(animateLight);

    // 🚪 Logout balik ke admin.php
    function logout() {
      localStorage.removeItem('osis_admin_auth');
      document.body.style.transition = "opacity 0.5s ease";
      document.body.style.opacity = 0;
      setTimeout(() => {
        window.location.href = "admin.php";
      }, 500);
    }

    // 📍 Navigate to sections (placeholder for future pages)
function navigateTo(section) {
  window.location.href = `${section}.html`;
}

    // ✨ Animasi fade-in dan particles
    window.addEventListener('load', () => {
      document.body.classList.add('loaded');
      createParticles();
    });

    // Create random particles
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
  </script>

  <!-- ✅ Script JS eksternal -->
  <script src="assets/js/dashboard.js"></script>

  <!-- 🔥 Firebase Config -->
  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/12.4.0/firebase-app.js";
    import { getDatabase, ref as dbRef, set, onValue, push, remove } from "https://www.gstatic.com/firebasejs/12.4.0/firebase-database.js";
    import { getStorage, ref as storageRef, uploadBytes, getDownloadURL, deleteObject } from "https://www.gstatic.com/firebasejs/12.4.0/firebase-storage.js";
    import { getAnalytics } from "https://www.gstatic.com/firebasejs/12.4.0/firebase-analytics.js";

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
    const analytics = getAnalytics(app);
    const db = getDatabase(app);
    const storage = getStorage(app);

    console.log("✅ Firebase connected:", app.name);
  </script>
</body>
</html>
