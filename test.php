<?php
session_start();
$_SESSION['ok'] = true;
echo "SESSION SET";
?>
<a href="dashboard.php">Masuk dashboard</a>
