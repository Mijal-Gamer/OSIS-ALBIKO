<?php
require 'connect-auth.php';

// Check if galeri table exists and has data
$query = "SELECT id, judul, LENGTH(foto) as foto_size, tipe_file FROM galeri LIMIT 5";
$result = mysqli_query($conn_auth, $query);

if ($result) {
    echo "<h2>Database Galeri Status:</h2>";
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>ID</th><th>Judul</th><th>Foto Size (bytes)</th><th>Mime Type</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['judul']) . "</td>";
            echo "<td>" . $row['foto_size'] . "</td>";
            echo "<td>" . $row['tipe_file'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br><p>✅ Total photos found: " . mysqli_num_rows($result) . "</p>";
    } else {
        echo "<p>❌ No photos in database yet</p>";
    }
} else {
    echo "<p>❌ Database error: " . mysqli_error($conn_auth) . "</p>";
}

// Test actual image display
echo "<h2>Test Image Display:</h2>";
$query = "SELECT id, judul, foto, tipe_file FROM galeri ORDER BY dibuat_at DESC LIMIT 1";
$result = mysqli_query($conn_auth, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $foto_data = base64_encode($row['foto']);
    $foto_src = 'data:' . $row['tipe_file'] . ';base64,' . $foto_data;
    
    echo "<p>ID: " . $row['id'] . "</p>";
    echo "<p>Judul: " . htmlspecialchars($row['judul']) . "</p>";
    echo "<p>Mime Type: " . $row['tipe_file'] . "</p>";
    echo "<p>Encoded Data Length: " . strlen($foto_data) . " chars</p>";
    echo "<hr>";
    echo "<p>Image Preview:</p>";
    echo "<img src='" . $foto_src . "' style='max-width: 300px; border: 2px solid #ccc;' alt='Test'>";
} else {
    echo "<p>No images to display</p>";
}

mysqli_close($conn_auth);
?>
