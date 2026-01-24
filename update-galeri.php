<?php
require 'connect-auth.php';

// Modify the galeri table to use TEXT for base64 data instead of LONGBLOB
$sql = "ALTER TABLE galeri MODIFY foto LONGTEXT NOT NULL";

if (mysqli_query($conn_auth, $sql)) {
    echo "✅ <b style='color: #00ff00;'>BERHASIL!</b> Tabel galeri diupdate!<br><br>";
    echo "Struktur tabel sekarang menggunakan LONGTEXT untuk menyimpan base64.<br>";
    echo "<a href='edit-konten.php'>Kembali ke Edit Konten</a>";
} else {
    echo "❌ <b style='color: #ff0000;'>GAGAL!</b><br>";
    echo "Error: " . mysqli_error($conn_auth);
}

mysqli_close($conn_auth);
?>
