<?php
require 'config.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_MAIN);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>