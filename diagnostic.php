<?php
/**
 * OSIS Astamayana - Diagnostic Page
 * Check system health, database connectivity, and data status
 */

require 'config.php';

$diagnostics = [
    'environment' => ENVIRONMENT,
    'timestamp' => date('Y-m-d H:i:s'),
    'checks' => []
];

// 1. Check Database Connection - osis_auth
$check_auth_db = [
    'name' => 'Database Connection (Auth)',
    'database' => DB_AUTH,
    'status' => false,
    'message' => ''
];

$conn_auth_test = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_AUTH);
if ($conn_auth_test) {
    $check_auth_db['status'] = true;
    $check_auth_db['message'] = "✅ Connected to " . DB_AUTH;
    mysqli_close($conn_auth_test);
} else {
    $check_auth_db['status'] = false;
    $check_auth_db['message'] = "❌ " . mysqli_connect_error();
}
$diagnostics['checks'][] = $check_auth_db;

// 2. Check Database Connection - main
$check_main_db = [
    'name' => 'Database Connection (Main)',
    'database' => DB_MAIN,
    'status' => false,
    'message' => ''
];

$conn_main_test = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_MAIN);
if ($conn_main_test) {
    $check_main_db['status'] = true;
    $check_main_db['message'] = "✅ Connected to " . DB_MAIN;
    mysqli_close($conn_main_test);
} else {
    $check_main_db['status'] = false;
    $check_main_db['message'] = "❌ " . mysqli_connect_error();
}
$diagnostics['checks'][] = $check_main_db;

// 3. Check Tables in Auth DB
if ($conn_auth_test) {
    $conn_auth_test = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_AUTH);
    $tables_query = "SHOW TABLES";
    $tables_result = mysqli_query($conn_auth_test, $tables_query);
    
    $tables = [];
    if ($tables_result && mysqli_num_rows($tables_result) > 0) {
        while ($row = mysqli_fetch_row($tables_result)) {
            $tables[] = $row[0];
        }
    }
    
    $required_tables = ['users', 'struktur_organisasi', 'galeri'];
    $missing_tables = array_diff($required_tables, $tables);
    
    $check_tables = [
        'name' => 'Database Tables',
        'status' => count($missing_tables) === 0,
        'message' => count($missing_tables) === 0 
            ? "✅ All required tables exist: " . implode(', ', $tables)
            : "❌ Missing tables: " . implode(', ', $missing_tables),
        'tables_found' => $tables,
        'tables_required' => $required_tables
    ];
    $diagnostics['checks'][] = $check_tables;
    
    mysqli_close($conn_auth_test);
}

// 4. Check Users Count & Details
$admin_users = [];
if ($conn_auth_test) {
    $conn_auth_test = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_AUTH);
    $users_query = "SELECT id, username, role FROM users";
    $users_result = mysqli_query($conn_auth_test, $users_query);
    $users_count = 0;
    
    if ($users_result && mysqli_num_rows($users_result) > 0) {
        while ($row = mysqli_fetch_assoc($users_result)) {
            $users_count++;
            $admin_users[] = [
                'id' => $row['id'],
                'username' => $row['username'],
                'role' => $row['role']
            ];
        }
    }
    
    $check_users = [
        'name' => 'Admin Users',
        'status' => $users_count > 0,
        'message' => $users_count > 0 
            ? "✅ Found " . $users_count . " user(s)"
            : "❌ No users found",
        'count' => $users_count,
        'users' => $admin_users
    ];
    $diagnostics['checks'][] = $check_users;
    
    mysqli_close($conn_auth_test);
}

// 5. Check Struktur Organisasi Data
if ($conn_auth_test) {
    $conn_auth_test = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_AUTH);
    $struktur_query = "SELECT COUNT(*) as count FROM struktur_organisasi";
    $struktur_result = mysqli_query($conn_auth_test, $struktur_query);
    $struktur_row = mysqli_fetch_assoc($struktur_result);
    $struktur_count = $struktur_row['count'];
    
    $check_struktur = [
        'name' => 'Struktur Organisasi',
        'status' => true,
        'message' => "ℹ️  Total " . $struktur_count . " anggota",
        'count' => $struktur_count
    ];
    $diagnostics['checks'][] = $check_struktur;
    
    mysqli_close($conn_auth_test);
}

// 6. Check Gallery Data
if ($conn_auth_test) {
    $conn_auth_test = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_AUTH);
    $galeri_query = "SELECT COUNT(*) as count FROM galeri";
    $galeri_result = mysqli_query($conn_auth_test, $galeri_query);
    $galeri_row = mysqli_fetch_assoc($galeri_result);
    $galeri_count = $galeri_row['count'];
    
    $check_galeri = [
        'name' => 'Gallery Photos',
        'status' => true,
        'message' => "ℹ️  Total " . $galeri_count . " photo(s)",
        'count' => $galeri_count
    ];
    $diagnostics['checks'][] = $check_galeri;
    
    mysqli_close($conn_auth_test);
}

// 7. Check Uploads Folder
$uploads_path = UPLOAD_DIR;
$check_uploads = [
    'name' => 'Uploads Folder',
    'status' => is_dir($uploads_path),
    'message' => is_dir($uploads_path) 
        ? "✅ Uploads folder exists and writable"
        : "❌ Uploads folder not found",
    'path' => $uploads_path,
    'writable' => is_writable($uploads_path)
];
$diagnostics['checks'][] = $check_uploads;

// 8. Check PHP Version
$check_php = [
    'name' => 'PHP Version',
    'status' => version_compare(PHP_VERSION, '7.4', '>='),
    'message' => "ℹ️  PHP " . PHP_VERSION,
    'version' => PHP_VERSION
];
$diagnostics['checks'][] = $check_php;

// 9. Check MySQLi Extension
$check_mysqli = [
    'name' => 'MySQLi Extension',
    'status' => extension_loaded('mysqli'),
    'message' => extension_loaded('mysqli') 
        ? "✅ MySQLi extension loaded"
        : "❌ MySQLi extension not loaded"
];
$diagnostics['checks'][] = $check_mysqli;

// 10. Check Database Credentials
$check_credentials = [
    'name' => 'Database Credentials',
    'status' => true,
    'message' => "ℹ️  Credentials configured",
    'host' => DB_HOST,
    'user' => DB_USER,
    'database_auth' => DB_AUTH,
    'database_main' => DB_MAIN
];
$diagnostics['checks'][] = $check_credentials;

// 11. Check Configuration File
$check_config = [
    'name' => 'Configuration Status',
    'environment' => ENVIRONMENT,
    'site_url' => SITE_URL,
    'status' => true,
    'message' => "✅ Configuration loaded (" . ucfirst(ENVIRONMENT) . ")"
];
$diagnostics['checks'][] = $check_config;

// Overall Status
$all_critical_passed = true;
foreach ($diagnostics['checks'] as $check) {
    if (isset($check['status']) && !$check['status']) {
        if (strpos($check['name'], 'Gallery') === false && 
            strpos($check['name'], 'Struktur') === false) {
            $all_critical_passed = false;
        }
    }
}
$diagnostics['overall_status'] = $all_critical_passed ? 'HEALTHY ✅' : 'HAS ISSUES ❌';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSIS Astamayana - Diagnostic</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #001a4d 0%, #004d99 50%, #00264d 100%);
            min-height: 100vh;
            padding: 30px 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: fadeInDown 0.6s ease-out;
        }

        .header h1 {
            color: #001a4d;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 32px;
        }

        .header h1 i {
            animation: rotate 3s linear infinite;
            color: #004d99;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #004d99;
        }

        .info-box label {
            display: block;
            color: #666;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .info-box value {
            display: block;
            color: #001a4d;
            font-size: 16px;
            font-weight: 600;
            word-break: break-all;
        }

        .status-badge {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: bold;
            margin-top: 20px;
            font-size: 18px;
            animation: slideInUp 0.6s ease-out;
        }

        .status-badge.healthy {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            box-shadow: 0 5px 15px rgba(21, 87, 36, 0.2);
        }

        .status-badge.issues {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            box-shadow: 0 5px 15px rgba(114, 28, 36, 0.2);
        }

        .diagnostics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .check-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #ddd;
            transition: all 0.3s ease;
            animation: slideInUp 0.5s ease-out backwards;
            cursor: pointer;
        }

        .check-card:nth-child(1) { animation-delay: 0.05s; }
        .check-card:nth-child(2) { animation-delay: 0.1s; }
        .check-card:nth-child(3) { animation-delay: 0.15s; }
        .check-card:nth-child(4) { animation-delay: 0.2s; }
        .check-card:nth-child(5) { animation-delay: 0.25s; }
        .check-card:nth-child(6) { animation-delay: 0.3s; }
        .check-card:nth-child(7) { animation-delay: 0.35s; }
        .check-card:nth-child(8) { animation-delay: 0.4s; }
        .check-card:nth-child(9) { animation-delay: 0.45s; }
        .check-card:nth-child(10) { animation-delay: 0.5s; }
        .check-card:nth-child(11) { animation-delay: 0.55s; }

        .check-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .check-card.passed {
            border-left-color: #28a745;
            background: linear-gradient(135deg, #f0fff4, #f8fffc);
        }

        .check-card.failed {
            border-left-color: #dc3545;
            background: linear-gradient(135deg, #fff5f5, #fff8f8);
        }

        .check-card.info {
            border-left-color: #17a2b8;
            background: linear-gradient(135deg, #f0f8fb, #f8fbfd);
        }

        .check-card h3 {
            color: #001a4d;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
        }

        .check-card p {
            color: #555;
            margin: 8px 0;
            font-size: 14px;
            line-height: 1.6;
        }

        .icon {
            font-size: 24px;
            animation: pulse 2s ease-in-out infinite;
        }

        .details {
            background: rgba(0, 0, 0, 0.05);
            padding: 15px;
            border-radius: 8px;
            margin-top: 12px;
            font-size: 13px;
            color: #555;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            border: 1px solid rgba(0, 0, 0, 0.08);
        }

        .details strong {
            color: #001a4d;
            display: block;
            margin-bottom: 5px;
        }

        .user-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .user-item {
            background: #f8f9fa;
            padding: 10px 12px;
            border-radius: 6px;
            border-left: 3px solid #004d99;
            font-size: 13px;
        }

        .user-item .username {
            color: #001a4d;
            font-weight: 600;
        }

        .user-item .role {
            color: #17a2b8;
            font-size: 12px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .footer {
            text-align: center;
            color: white;
            margin-top: 50px;
            padding: 20px;
            animation: fadeInDown 0.8s ease-out;
        }

        .footer a {
            color: #00ffff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            text-decoration: underline;
            color: #ffffff;
        }

        .button-group {
            text-align: center;
            margin-top: 30px;
            animation: slideInUp 0.8s ease-out;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 35px;
            margin: 0 10px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #001a4d, #004d99);
            color: white;
            box-shadow: 0 5px 15px rgba(0, 74, 153, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 74, 153, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
            box-shadow: 0 5px 15px rgba(23, 162, 184, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(23, 162, 184, 0.4);
        }

        .btn i {
            font-size: 18px;
        }

        .refresh-indicator {
            display: inline-block;
            padding: 8px 15px;
            background: rgba(0, 255, 255, 0.2);
            border: 1px solid rgba(0, 255, 255, 0.5);
            border-radius: 20px;
            color: #00ffff;
            font-size: 12px;
            margin-top: 15px;
        }

        .refresh-indicator i {
            animation: rotate 2s linear infinite;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                <i class="ri-stethoscope-line"></i>
                OSIS Astamayana - System Diagnostic
            </h1>
            
            <div class="info-grid">
                <div class="info-box">
                    <label>Environment</label>
                    <value><?php echo ucfirst($diagnostics['environment']); ?></value>
                </div>
                <div class="info-box">
                    <label>Database Host</label>
                    <value><?php echo DB_HOST; ?></value>
                </div>
                <div class="info-box">
                    <label>Database User</label>
                    <value><?php echo DB_USER; ?></value>
                </div>
                <div class="info-box">
                    <label>Auth Database</label>
                    <value><?php echo DB_AUTH; ?></value>
                </div>
                <div class="info-box">
                    <label>Main Database</label>
                    <value><?php echo DB_MAIN; ?></value>
                </div>
                <div class="info-box">
                    <label>Scan Time</label>
                    <value><?php echo $diagnostics['timestamp']; ?></value>
                </div>
            </div>

            <div class="status-badge <?php echo $all_critical_passed ? 'healthy' : 'issues'; ?>">
                <?php echo $diagnostics['overall_status']; ?>
            </div>

            <div class="refresh-indicator">
                <i class="ri-refresh-line"></i> Auto-refresh setiap 30 detik
            </div>
        </div>

        <div class="diagnostics-grid">
            <?php foreach ($diagnostics['checks'] as $check): ?>
                <?php
                    $status_class = 'info';
                    $icon = 'ri-information-line';
                    
                    if (isset($check['status'])) {
                        if ($check['status']) {
                            $status_class = 'passed';
                            $icon = 'ri-checkbox-circle-line';
                        } else {
                            $status_class = 'failed';
                            $icon = 'ri-close-circle-line';
                        }
                    }
                ?>
                <div class="check-card <?php echo $status_class; ?>">
                    <h3>
                        <i class="ri-icon <?php echo $icon; ?>"></i>
                        <?php echo $check['name']; ?>
                    </h3>
                    <p><?php echo $check['message']; ?></p>

                    <?php if (isset($check['database'])): ?>
                        <div class="details">
                            <strong>Database:</strong> <?php echo $check['database']; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($check['tables_found'])): ?>
                        <div class="details">
                            <strong>Found Tables:</strong>
                            <?php echo implode(', ', $check['tables_found']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($check['users']) && count($check['users']) > 0): ?>
                        <div class="details">
                            <strong>Admin Users:</strong>
                            <div class="user-list">
                                <?php foreach ($check['users'] as $user): ?>
                                    <div class="user-item">
                                        <span class="username">👤 <?php echo htmlspecialchars($user['username']); ?></span>
                                        <span class="role">Role: <?php echo htmlspecialchars($user['role']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($check['count']) && !isset($check['users'])): ?>
                        <div class="details">
                            <strong>Total Count:</strong> <?php echo $check['count']; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($check['path'])): ?>
                        <div class="details">
                            <strong>Path:</strong> <?php echo $check['path']; ?><br>
                            <strong>Writable:</strong> <?php echo $check['writable'] ? '✅ Yes' : '❌ No'; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($check['version'])): ?>
                        <div class="details">
                            <strong>Version:</strong> <?php echo $check['version']; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="button-group">
            <a href="index.php" class="btn btn-primary">
                <i class="ri-home-line"></i> Go to Homepage
            </a>
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="ri-dashboard-line"></i> Go to Dashboard
            </a>
        </div>

        <div class="footer">
            <p>OSIS Astamayana - System Diagnostic Tool</p>
            <p>For support, contact the administrator</p>
        </div>
    </div>

    <script>
        // Auto-refresh every 30 seconds
        setTimeout(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
