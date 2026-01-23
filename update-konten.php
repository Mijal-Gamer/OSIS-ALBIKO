<?php
// 🔒 FILE INI HANYA UNTUK MENYIMPAN DATA KE DATABASE
// TIDAK ADA HTML, TIDAK ADA CSS, TIDAK ADA JS

include "connect.php";
header('Content-Type: application/json');

// Cek koneksi database
if (!$conn) {
    echo json_encode([
        'status' => 'error', 
        'message' => '❌ Koneksi database gagal!',
        'detail' => 'Hubungi administrator. Periksa connect.php'
    ]);
    exit();
}

// Cek field mana yang kosong
$errors = [];
if (empty($_POST['judul_tentang'])) $errors[] = 'Judul Tentang';
if (empty($_POST['isi_tentang'])) $errors[] = 'Isi Tentang';
if (empty($_POST['judul_kegiatan'])) $errors[] = 'Judul Kegiatan';
if (empty($_POST['isi_kegiatan'])) $errors[] = 'Isi Kegiatan';
if (empty($_POST['instagram'])) $errors[] = 'Instagram';
if (empty($_POST['tiktok'])) $errors[] = 'TikTok';

if (!empty($errors)) {
    echo json_encode([
        'status' => 'error', 
        'message' => '❌ Validasi data gagal!',
        'detail' => 'Field kosong: ' . implode(', ', $errors)
    ]);
    exit();
}

// Ambil data dari form (sudah di-trim & di-escape)
$judul_tentang = trim(mysqli_real_escape_string($conn, $_POST['judul_tentang']));
$isi_tentang = trim(mysqli_real_escape_string($conn, $_POST['isi_tentang']));
$judul_kegiatan = trim(mysqli_real_escape_string($conn, $_POST['judul_kegiatan']));
$isi_kegiatan = trim(mysqli_real_escape_string($conn, $_POST['isi_kegiatan']));
$instagram = trim(mysqli_real_escape_string($conn, $_POST['instagram']));
$tiktok = trim(mysqli_real_escape_string($conn, $_POST['tiktok']));

// Cek tabel halaman ada atau tidak
$check_table = mysqli_query($conn, "SHOW TABLES LIKE 'halaman'");
if (mysqli_num_rows($check_table) == 0) {
    echo json_encode([
        'status' => 'error', 
        'message' => '❌ Tabel database tidak ditemukan!',
        'detail' => 'Tabel "halaman" belum dibuat. Buat tabel terlebih dahulu.'
    ]);
    exit();
}

// Cek data dengan id=1 ada atau tidak
$check_data = mysqli_query($conn, "SELECT id FROM halaman WHERE id=1");
if (mysqli_num_rows($check_data) == 0) {
    // Insert data baru jika tidak ada
    $insert_query = "
        INSERT INTO halaman (id, judul_tentang, isi_tentang, judul_kegiatan, isi_kegiatan, instagram, tiktok)
        VALUES (1, '$judul_tentang', '$isi_tentang', '$judul_kegiatan', '$isi_kegiatan', '$instagram', '$tiktok')
    ";
    
    if (mysqli_query($conn, $insert_query)) {
        echo json_encode([
            'status' => 'success', 
            'message' => '✅ Data baru berhasil disimpan ke database!'
        ]);
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => '❌ Gagal insert data baru!',
            'detail' => 'Error: ' . mysqli_error($conn),
            'query_error' => mysqli_errno($conn)
        ]);
    }
    exit();
}

// Update ke database
$query = "
    UPDATE halaman 
    SET judul_tentang='$judul_tentang', 
        isi_tentang='$isi_tentang',
        judul_kegiatan='$judul_kegiatan',
        isi_kegiatan='$isi_kegiatan',
        instagram='$instagram',
        tiktok='$tiktok'
    WHERE id=1
";

if (mysqli_query($conn, $query)) {
    echo json_encode([
        'status' => 'success', 
        'message' => '✅ Konten berhasil diperbarui ke database!'
    ]);
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => '❌ Gagal menyimpan ke database!',
        'detail' => 'Error: ' . mysqli_error($conn),
        'query_error' => mysqli_errno($conn)
    ]);
}

exit();
?>
