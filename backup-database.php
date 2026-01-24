<?php
/**
 * Database Backup Script
 * Creates SQL backup of current database structure and data
 */

require 'config.php';

// Get database names
$databases = [DB_AUTH, DB_MAIN];
$backup_content = "-- OSIS Astamayana Database Backup\n";
$backup_content .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
$backup_content .= "-- Environment: " . ENVIRONMENT . "\n\n";

foreach ($databases as $db_name) {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, $db_name);
    
    if (!$conn) {
        die("Connection failed for $db_name: " . mysqli_connect_error());
    }
    
    $backup_content .= "\n-- ============================================\n";
    $backup_content .= "-- Database: $db_name\n";
    $backup_content .= "-- ============================================\n\n";
    $backup_content .= "CREATE DATABASE IF NOT EXISTS `$db_name`;\n";
    $backup_content .= "USE `$db_name`;\n\n";
    
    // Get all tables
    $tables_query = "SHOW TABLES";
    $tables_result = mysqli_query($conn, $tables_query);
    
    if ($tables_result && mysqli_num_rows($tables_result) > 0) {
        while ($table = mysqli_fetch_row($tables_result)) {
            $table_name = $table[0];
            
            // Get CREATE TABLE statement
            $create_table = "SHOW CREATE TABLE `$table_name`";
            $create_result = mysqli_query($conn, $create_table);
            $create_row = mysqli_fetch_row($create_result);
            
            $backup_content .= $create_row[1] . ";\n\n";
            
            // Get all data
            $data_query = "SELECT * FROM `$table_name`";
            $data_result = mysqli_query($conn, $data_query);
            
            if (mysqli_num_rows($data_result) > 0) {
                while ($row = mysqli_fetch_assoc($data_result)) {
                    $columns = array_keys($row);
                    $values = array_map(function($val) use ($conn) {
                        return "'" . mysqli_real_escape_string($conn, $val) . "'";
                    }, array_values($row));
                    
                    $backup_content .= "INSERT INTO `$table_name` (`" . 
                                     implode("`, `", $columns) . 
                                     "`) VALUES (" . 
                                     implode(", ", $values) . 
                                     ");\n";
                }
            }
            $backup_content .= "\n";
        }
    }
    
    mysqli_close($conn);
}

// Download file
header('Content-Type: application/sql');
header('Content-Disposition: attachment; filename="osis-backup-' . date('Y-m-d-H-i-s') . '.sql"');
echo $backup_content;
?>
