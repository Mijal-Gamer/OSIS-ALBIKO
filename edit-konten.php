<?php include 'connect.php'; ?>

<?php
if (isset($_POST['simpan'])) {
  $judul = mysqli_real_escape_string($conn, $_POST['judul']);
  $isi = mysqli_real_escape_string($conn, $_POST['isi']);
  mysqli_query($conn, "UPDATE halaman_utama SET judul='$judul', isi='$isi' WHERE id=1");
  echo "<script>alert('Berhasil disimpan!'); window.location='edit-konten.php';</script>";
}

$result = mysqli_query($conn, "SELECT * FROM halaman_utama WHERE id=1");
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Konten | OSIS Astamayana</title>
  <link rel="icon" type="image/png" href="https://raw.githubusercontent.com/Mijal-Gamer/OSIS-ALBIKO/refs/heads/main/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
  <style>
    /* Reuse similar styles from dashboard.html for consistency */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { background: radial-gradient(circle at center, #08122a, #020409); color: white; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; }
    header { width: 100%; padding: 20px; text-align: center; background: rgba(0, 255, 255, 0.05); border-bottom: 1px solid rgba(0, 255, 255, 0.2); }
    header img { width: 60px; border-radius: 50%; }
    header h1 { color: #00e0ff; font-size: 24px; margin-top: 10px; }
    .container { max-width: 800px; margin: 40px auto; padding: 20px; background: rgba(255, 255, 255, 0.05); border-radius: 20px; border: 1px solid rgba(0, 255, 255, 0.2); }
    textarea { width: 100%; height: 200px; background: rgba(0,0,0,0.5); color: white; border: 1px solid #00e0ff; border-radius: 10px; padding: 10px; resize: vertical; }
    button { background: #00e0ff; border: none; padding: 12px 25px; border-radius: 10px; color: #020409; cursor: pointer; margin: 10px; transition: all 0.3s; }
    button:hover { background: #00ffff; transform: scale(1.05); }
    .back-btn { background: #ff4b4b; color: white; }
    .back-btn:hover { background: #ff6666; }
  </style>
</head>
<body>
  <header>
    <img src="https://raw.githubusercontent.com/Mijal-Gamer/OSIS-ALBIKO/refs/heads/main/favicon.ico" alt="Logo OSIS">
    <h1>Edit Konten OSIS Astamayana</h1>
  </header>

  <div class="container">
    <h2>Edit Konten Utama</h2>
    <textarea id="contentTextarea" placeholder="Masukkan konten di sini..."></textarea>
    <button onclick="saveContent()">Simpan Konten</button>
    <button class="back-btn" onclick="goBack()">Kembali ke Dashboard</button>
  </div>

  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/12.4.0/firebase-app.js";
    import { getDatabase, ref, set, onValue } from "https://www.gstatic.com/firebasejs/12.4.0/firebase-database.js";

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

    // Check auth
    if (localStorage.getItem('osis_admin_auth') !== 'true') {
      window.location.href = "admin.html";
    }

    // Load existing content
    const contentRef = ref(db, 'content/main');
    onValue(contentRef, (snapshot) => {
      const data = snapshot.val();
      if (data) {
        document.getElementById('contentTextarea').value = data.text || '';
      }
    });

    // Save content
    window.saveContent = function() {
      const text = document.getElementById('contentTextarea').value;
      set(contentRef, { text: text })
        .then(() => alert('Konten berhasil disimpan!'))
        .catch((error) => alert('Error: ' + error.message));
    };

    window.goBack = function() {
      window.location.href = "dashboard.html";
    };
  </script>
</body>
</html>
