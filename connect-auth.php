<?php
require 'config.php';

$conn_auth = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_AUTH);

if (!$conn_auth) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn_auth, "utf8mb4");
?>