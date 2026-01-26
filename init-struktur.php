<?php
require 'connect-auth.php';

// Step 1: Create table
$create_table = "CREATE TABLE IF NOT EXISTS struktur_organisasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipe VARCHAR(50) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    nama VARCHAR(255) NOT NULL,
    posisi VARCHAR(100) NOT NULL,
    urutan INT DEFAULT 0,
    dibuat_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    diupdate_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_struktur (tipe, kategori, posisi)
)";

if (mysqli_query($conn_auth, $create_table)) {
    echo "<h2 style='color: #00ff00;'>✅ Tabel struktur_organisasi berhasil dibuat!</h2>";
} else {
    echo "<h2 style='color: #ff6b6b;'>⚠️ Tabel sudah ada atau error: " . mysqli_error($conn_auth) . "</h2>";
}

// Step 2: Clear existing data (optional - uncomment jika mau reset)
// mysqli_query($conn_auth, "TRUNCATE TABLE struktur_organisasi");

// Step 3: Insert data
$data = [
    // Pembina
    ['pengurus', 'Pembina', 'Valensia Ihsa Mahendra, S.Pd', 'Pembina', 1],
    ['pengurus', 'Pembina', 'Afifiah Abidah, S.Pd', 'Wakil Pembina', 2],
    
    // Pengurus Inti
    ['pengurus', 'Pengurus Inti', 'Qori\' Ahsan', 'Ketua', 1],
    ['pengurus', 'Pengurus Inti', 'Arin Kusuma Dewi', 'Wakil Ketua', 2],
    ['pengurus', 'Pengurus Inti', 'Areta Aquilani Hanifia', 'Sekretaris I', 3],
    ['pengurus', 'Pengurus Inti', 'Muhammad Daffa Azalia', 'Sekretaris II', 4],
    ['pengurus', 'Pengurus Inti', 'Felda Muffarrahit Setyaro', 'Bendahara I', 5],
    ['pengurus', 'Pengurus Inti', 'Abdurrahman Hanif Al Fathin', 'Bendahara II', 6],
    
    // KPA
    ['divisi', 'KPA', 'Kenzie Mirza Manggala', 'Anggota', 1],
    ['divisi', 'KPA', 'Abyan Rafiandra Maheswara', 'Anggota', 2],
    ['divisi', 'KPA', 'Satria Wicaksono', 'Anggota', 3],
    ['divisi', 'KPA', 'Amanda Ayu Lestari', 'Anggota', 4],
    ['divisi', 'KPA', 'Alitta Belicia Zanetha Tsary', 'Anggota', 5],
    ['divisi', 'KPA', 'Nayla Zhafira M.', 'Anggota', 6],
    
    // Korseni
    ['divisi', 'Korseni', 'Akia Destin Kenzie Nararya', 'Anggota', 1],
    ['divisi', 'Korseni', 'Arbian Happy Fairuz', 'Anggota', 2],
    ['divisi', 'Korseni', 'Az Zahra Putri Langit', 'Anggota', 3],
    ['divisi', 'Korseni', 'Naura Mulya Arsyane', 'Anggota', 4],
    ['divisi', 'Korseni', 'Prabu Lingga Jatmika', 'Anggota', 5],
    
    // Komdis
    ['divisi', 'Komdis', 'Azka Muhammad Alfarizyq', 'Anggota', 1],
    ['divisi', 'Komdis', 'Desta Surya Prasetya', 'Anggota', 2],
    ['divisi', 'Komdis', 'Fauziah Kanaya Yusuf', 'Anggota', 3],
    ['divisi', 'Komdis', 'Sabrina Rigel Segoro', 'Anggota', 4],
    ['divisi', 'Komdis', 'Agha Akbar Azizi', 'Anggota', 5],
    ['divisi', 'Komdis', 'Malika Naajla Islammadina', 'Anggota', 6],
    
    // Rohis
    ['divisi', 'Rohis', 'Daffa Syadad Nur Faisal', 'Anggota', 1],
    ['divisi', 'Rohis', 'Ibrohim Abqori', 'Anggota', 2],
    ['divisi', 'Rohis', 'Rafeyfa Fachrya Hudi', 'Anggota', 3],
    ['divisi', 'Rohis', 'Naidah Tsabita Khairunnisa', 'Anggota', 4],
    ['divisi', 'Rohis', 'Faeya Hariz Sadewa', 'Anggota', 5],
    ['divisi', 'Rohis', 'Zayna Asfa Fawziya', 'Anggota', 6],
    
    // APK
    ['divisi', 'APK', 'Irsyad Pradipta Yuswardhana', 'Anggota', 1],
    ['divisi', 'APK', 'Meidianty Naila Azizah', 'Anggota', 2],
    ['divisi', 'APK', 'Belva Ayunindya Agata Sumarno', 'Anggota', 3],
    ['divisi', 'APK', 'Nadif Val\'azzam Firdaus Bianca', 'Anggota', 4],
    ['divisi', 'APK', 'Zinedine Al Faruqi', 'Anggota', 5],
    ['divisi', 'APK', 'Janeta Humaira Rahadatul Aisy', 'Anggota', 6],
    
    // Humas
    ['divisi', 'Humas', 'Mifzal Kanzie Raharjo', 'Anggota', 1],
    ['divisi', 'Humas', 'Safira Faizah Adristi', 'Anggota', 2],
    ['divisi', 'Humas', 'Habibah Dzakiyyah Ashidiq', 'Anggota', 3],
    ['divisi', 'Humas', 'Riquel Zifdan Halevy', 'Anggota', 4],
    ['divisi', 'Humas', 'Alif Irsyad Khoirullah', 'Anggota', 5],
    ['divisi', 'Humas', 'Ayra Charissa Putri', 'Anggota', 6],
];

$success = 0;
$duplicate = 0;

foreach ($data as $item) {
    $stmt = mysqli_prepare($conn_auth, "INSERT IGNORE INTO struktur_organisasi (tipe, kategori, nama, posisi, urutan) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssi", $item[0], $item[1], $item[2], $item[3], $item[4]);
    
    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $success++;
        } else {
            $duplicate++;
        }
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn_auth);

echo "<br><h3 style='color: #00e0ff;'>📊 Hasil Import Data:</h3>";
echo "<p>✅ Data baru yang ditambah: <b>$success</b></p>";
echo "<p>⚠️ Data duplikat (skip): <b>$duplicate</b></p>";
echo "<br><a href='index.php' style='padding: 10px 20px; background: #00e0ff; color: black; text-decoration: none; border-radius: 5px; font-weight: bold;'>➡️ Lihat Index.php</a>";
echo " | ";
echo "<a href='edit-konten.php' style='padding: 10px 20px; background: #00e0ff; color: black; text-decoration: none; border-radius: 5px; font-weight: bold;'>➡️ Kelola di Edit Konten</a>";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Setup Struktur Organisasi</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #08122a, #020409, #0d1b2a);
            color: white;
            font-family: 'Poppins', sans-serif;
            padding: 40px;
            text-align: center;
        }
        h2, h3 { color: #00e0ff; }
        p { font-size: 1.1em; margin: 10px 0; }
    </style>
</head>
<body>
</body>
</html>
