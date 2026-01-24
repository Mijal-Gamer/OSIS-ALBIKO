<?php
// Test database connection
require 'connect.php';
require 'connect-auth.php';

echo "<h1>Test Database Connection</h1>";

// Test main database
echo "<h2>Main Database (osis)</h2>";
if ($conn) {
    echo "<p style='color: green;'>✓ Connected to osis</p>";
    $result = mysqli_query($conn, "SHOW TABLES");
    echo "<p>Tables:</p><ul>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>✗ Failed to connect</p>";
}

// Test auth database
echo "<h2>Auth Database (osis_auth)</h2>";
if ($conn_auth) {
    echo "<p style='color: green;'>✓ Connected to osis_auth</p>";
    $result = mysqli_query($conn_auth, "SHOW TABLES");
    echo "<p>Tables:</p><ul>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>✗ Failed to connect</p>";
}
?>