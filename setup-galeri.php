<?php
require 'connect-auth.php';

// Create table
$sql = "CREATE TABLE IF NOT EXISTS galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    foto LONGBLOB NOT NULL,
    tipe_file VARCHAR(50) NOT NULL,
    ukuran_file INT,
    dibuat_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn_auth, $sql)) {
    echo "✅ <b style='color: #00ff00;'>BERHASIL!</b> Tabel galeri sudah dibuat!<br><br>";
    echo "Sekarang Anda bisa:<br>";
    echo "1. <a href='dashboard.php'>Kembali ke Dashboard</a><br>";
    echo "2. <a href='edit-konten.php'>Buka Edit Konten untuk manage galeri</a><br>";
} else {
    echo "❌ <b style='color: #ff0000;'>GAGAL!</b><br>";
    echo "Error: " . mysqli_error($conn_auth);
}

mysqli_close($conn_auth);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Setup Galeri</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #08122a, #020409, #0d1b2a);
            color: white;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        a {
            color: #00e0ff;
            text-decoration: none;
            margin: 10px 0;
            display: inline-block;
            padding: 10px 20px;
            border: 2px solid #00e0ff;
            border-radius: 8px;
            transition: all 0.3s;
        }
        a:hover {
            background: #00e0ff;
            color: #000;
        }
    </style>
</head>
<body>
</body>
</html>
