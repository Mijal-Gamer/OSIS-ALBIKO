<?php
// API endpoint untuk statistics
require 'auth-check.php';
require 'connect.php';
require 'connect-auth.php';
require 'helpers.php';

header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'get_stats':
        // Get total users
        $users = queryAuth("SELECT COUNT(*) as total FROM users");
        $user_count = fetchOne($users)['total'];

        // Get total feedback (dari Firebase atau database)
        $stats = [
            'users' => $user_count,
            'content_pages' => 1,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        echo json_encode($stats);
        break;

    case 'get_logs':
        $logs = queryAuth("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 10");
        $log_data = fetchAll($logs);
        echo json_encode($log_data);
        break;

    default:
        jsonResponse(false, 'Action tidak valid');
}
?>