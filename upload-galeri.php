<?php
require 'auth-check.php';
require 'connect-auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $foto = $_POST['foto'] ?? '';
    $tipe_file = $_POST['tipe_file'] ?? 'image/jpeg';
    $ukuran_file = $_POST['ukuran_file'] ?? 0;

    // Decode base64 to binary
    $foto_binary = base64_decode($foto, true);

    if (empty($judul) || empty($foto_binary)) {
        echo json_encode(['status' => 'error', 'message' => 'Judul dan foto harus diisi!']);
        exit;
    }

    // Alternative method: Store as base64 string directly (simpler and more reliable)
    $stmt = mysqli_prepare($conn_auth, "INSERT INTO galeri (judul, deskripsi, foto, tipe_file, ukuran_file) VALUES (?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($conn_auth)]);
        exit;
    }

    // Use base64 string instead of binary
    mysqli_stmt_bind_param($stmt, "ssssi", $judul, $deskripsi, $foto, $tipe_file, $ukuran_file);
    
    if (mysqli_stmt_execute($stmt)) {
        $insert_id = mysqli_insert_id($conn_auth);
        echo json_encode(['status' => 'success', 'message' => 'Foto berhasil diupload!', 'id' => $insert_id]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal upload: ' . mysqli_error($conn_auth)]);
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}

mysqli_close($conn_auth);
?>
