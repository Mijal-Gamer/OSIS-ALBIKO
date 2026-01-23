<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jadwal Kegiatan | OSIS Astamayana</title>
  <link rel="icon" type="image/png" href="https://raw.githubusercontent.com/Mijal-Gamer/OSIS-ALBIKO/refs/heads/main/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
  <style>
    /* Similar styles */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { background: radial-gradient(circle at center, #08122a, #020409); color: white; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; }
    header { width: 100%; padding: 20px; text-align: center; background: rgba(0, 255, 255, 0.05); border-bottom: 1px solid rgba(0, 255, 255, 0.2); }
    header img { width: 60px; border-radius: 50%; }
    header h1 { color: #00e0ff; font-size: 24px; margin-top: 10px; }
    .container { max-width: 1000px; margin: 40px auto; padding: 20px; background: rgba(255, 255, 255, 0.05); border-radius: 20px; border: 1px solid rgba(0, 255, 255, 0.2); }
    input, textarea { width: 100%; margin: 10px 0; padding: 10px; background: rgba(0,0,0,0.5); color: white; border: 1px solid #00e0ff; border-radius: 10px; }
    button { background: #00e0ff; border: none; padding: 12px 25px; border-radius: 10px; color: #020409; cursor: pointer; margin: 10px; transition: all 0.3s; }
    button:hover { background: #00ffff; transform: scale(1.05); }
    .back-btn { background: #ff4b4b; color: white; }
    .back-btn:hover { background: #ff6666; }
    .events { margin-top: 20px; }
    .event { background: rgba(0,0,0,0.3); padding: 15px; margin: 10px 0; border-radius: 10px; border: 1px solid rgba(0,255,255,0.2); }
    .event h3 { color: #00e0ff; }
    .delete-btn { background: #ff4b4b; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; }
  </style>
</head>
<body>
  <header>
    <img src="https://raw.githubusercontent.com/Mijal-Gamer/OSIS-ALBIKO/refs/heads/main/favicon.ico" alt="Logo OSIS">
    <h1>Jadwal Kegiatan OSIS Astamayana</h1>
  </header>

  <div class="container">
    <h2>Tambah Kegiatan Baru</h2>
    <input type="text" id="eventTitle" placeholder="Judul Kegiatan">
    <input type="date" id="eventDate">
    <textarea id="eventDesc" placeholder="Deskripsi Kegiatan"></textarea>
    <button onclick="addEvent()">Tambah Kegiatan</button>
    <button class="back-btn" onclick="goBack()">Kembali ke Dashboard</button>

    <h2>Daftar Kegiatan</h2>
    <div class="events" id="events"></div>
  </div>

  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/12.4.0/firebase-app.js";
    import { getDatabase, ref, set, onValue, push, remove } from "https://www.gstatic.com/firebasejs/12.4.0/firebase-database.js";

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

    // Load events
    const eventsRef = ref(db, 'events');
    onValue(eventsRef, (snapshot) => {
      const eventsDiv = document.getElementById('events');
      eventsDiv.innerHTML = '';
      const data = snapshot.val();
      if (data) {
        Object.keys(data).forEach(key => {
          const event = data[key];
          const div = document.createElement('div');
          div.className = 'event';
          div.innerHTML = `<h3>${event.title}</h3><p><strong>Tanggal:</strong> ${event.date}</p><p>${event.description}</p><button class="delete-btn" onclick="deleteEvent('${key}')">Hapus</button>`;
          eventsDiv.appendChild(div);
        });
      }
    });

    // Add event
    window.addEvent = function() {
      const title = document.getElementById('eventTitle').value;
      const date = document.getElementById('eventDate').value;
      const desc = document.getElementById('eventDesc').value;
      if (!title || !date || !desc) return alert('Isi semua field!');
      const newRef = push(eventsRef);
      set(newRef, { title: title, date: date, description: desc })
        .then(() => {
          alert('Kegiatan berhasil ditambahkan!');
          document.getElementById('eventTitle').value = '';
          document.getElementById('eventDate').value = '';
          document.getElementById('eventDesc').value = '';
        })
        .catch((error) => alert('Error: ' + error.message));
    };

    // Delete event
    window.deleteEvent = function(key) {
      remove(ref(db, `events/${key}`))
        .then(() => alert('Kegiatan berhasil dihapus!'))
        .catch((error) => alert('Error: ' + error.message));
    };

    window.goBack = function() {
      window.location.href = "dashboard.html";
    };
  </script>
</body>
</html>
