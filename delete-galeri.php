<?php
require 'auth-check.php';
require 'connect-auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
        exit;
    }

    // Delete from database
    $stmt = mysqli_prepare($conn_auth, "DELETE FROM galeri WHERE id = ?");
    
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($conn_auth)]);
        exit;
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success', 'message' => 'Foto berhasil dihapus!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal hapus: ' . mysqli_error($conn_auth)]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}

mysqli_close($conn_auth);
?>
