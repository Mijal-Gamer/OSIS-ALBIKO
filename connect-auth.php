<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'osis_auth';

$conn_auth = mysqli_connect($host, $user, $password, $database);

if (!$conn_auth) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn_auth, "utf8mb4");
?>