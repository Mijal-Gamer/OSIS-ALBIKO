<?php
$host = "localhost";
$user = "wwoiodev_Admin";
$pass = "qwertyuiop89001";
$db = "wwoiodev_osis_auth";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    echo "❌ Connection Error: " . mysqli_connect_error();
    exit;
}

$sql = "UPDATE users SET password='osisnew2025' WHERE username='admin'";

if (mysqli_query($conn, $sql)) {
    echo "✅ Password updated successfully!<br>";
    echo "Username: <strong>admin</strong><br>";
    echo "Password: <strong>osisnew2025</strong><br>";
    echo "<br><a href='login.php'>Go to Login</a>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
