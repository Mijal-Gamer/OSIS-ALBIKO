<?php
require 'auth-check.php';
require 'connect-auth.php';

header('Content-Type: application/json');

// Get all gallery items
$query = "SELECT id, judul, deskripsi, foto, tipe_file FROM galeri ORDER BY dibuat_at DESC";
$result = mysqli_query($conn_auth, $query);

$items = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Encode binary data to base64
        $foto_base64 = base64_encode($row['foto']);
        
        $items[] = [
            'id' => $row['id'],
            'judul' => $row['judul'],
            'deskripsi' => $row['deskripsi'],
            'foto' => $foto_base64,
            'tipe_file' => $row['tipe_file']
        ];
    }
}

echo json_encode(['status' => 'success', 'items' => $items]);

mysqli_close($conn_auth);
?>
