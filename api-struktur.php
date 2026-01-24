<?php
require 'auth-check.php';
require 'connect-auth.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'get') {
    $tipe = $_GET['tipe'] ?? 'semua';
    
    if ($tipe === 'semua') {
        $query = "SELECT * FROM struktur_organisasi ORDER BY tipe, kategori, urutan";
    } else {
        $query = "SELECT * FROM struktur_organisasi WHERE tipe = ? ORDER BY kategori, urutan";
    }
    
    if ($stmt = mysqli_prepare($conn_auth, $query)) {
        if ($tipe !== 'semua') {
            mysqli_stmt_bind_param($stmt, "s", $tipe);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        
        echo json_encode(['status' => 'success', 'items' => $items]);
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn_auth)]);
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $tipe = $_POST['tipe'] ?? '';
        $kategori = $_POST['kategori'] ?? '';
        $nama = $_POST['nama'] ?? '';
        $posisi = $_POST['posisi'] ?? '';
        $urutan = intval($_POST['urutan'] ?? 0);
        
        if (empty($tipe) || empty($kategori) || empty($nama) || empty($posisi)) {
            echo json_encode(['status' => 'error', 'message' => 'Semua field harus diisi!']);
            exit;
        }
        
        $stmt = mysqli_prepare($conn_auth, "INSERT INTO struktur_organisasi (tipe, kategori, nama, posisi, urutan) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssi", $tipe, $kategori, $nama, $posisi, $urutan);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil ditambah!', 'id' => mysqli_insert_id($conn_auth)]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal: ' . mysqli_error($conn_auth)]);
        }
        mysqli_stmt_close($stmt);
    }
    elseif ($action === 'update') {
        $id = intval($_POST['id'] ?? 0);
        $nama = $_POST['nama'] ?? '';
        $posisi = $_POST['posisi'] ?? '';
        
        if ($id <= 0 || empty($nama) || empty($posisi)) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak valid!']);
            exit;
        }
        
        $stmt = mysqli_prepare($conn_auth, "UPDATE struktur_organisasi SET nama = ?, posisi = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $nama, $posisi, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil diupdate!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal: ' . mysqli_error($conn_auth)]);
        }
        mysqli_stmt_close($stmt);
    }
    elseif ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        
        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid!']);
            exit;
        }
        
        $stmt = mysqli_prepare($conn_auth, "DELETE FROM struktur_organisasi WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal: ' . mysqli_error($conn_auth)]);
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn_auth);
?>
