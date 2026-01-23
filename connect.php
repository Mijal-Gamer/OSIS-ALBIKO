<?php
$host = "localhost";
$user = "wwoiodev_osisuser";  // username database kamu
$pass = "^4IwNV*3ziShrXyk";      // ganti dengan password user yang kamu buat
$dbname = "wwoiodev_osisweb"; // nama database kamu

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Kalau berhasil, bisa aktif
// echo "Koneksi berhasil!";
?>
