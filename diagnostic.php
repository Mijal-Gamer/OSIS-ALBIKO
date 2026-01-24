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

// 4. Check Users Count
if ($conn_auth_test) {
    $conn_auth_test = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_AUTH);
    $users_query = "SELECT COUNT(*) as count FROM users";
    $users_result = mysqli_query($conn_auth_test, $users_query);
    $users_row = mysqli_fetch_assoc($users_result);
    $users_count = $users_row['count'];
    
    $check_users = [
        'name' => 'Admin Users',
        'status' => $users_count > 0,
        'message' => $users_count > 0 
            ? "✅ Found " . $users_count . " user(s)"
            : "❌ No users found",
        'count' => $users_count
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
        'status' => $struktur_count > 0,
        'message' => $struktur_count > 0 
            ? "✅ Found " . $struktur_count . " anggota"
            : "⚠️  No anggota data yet",
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
        'message' => "ℹ️  Total " . $galeri_count . " photo(s) in gallery",
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
        ? "✅ Uploads folder exists at: " . $uploads_path
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
        ? "✅ MySQLi extension is loaded"
        : "❌ MySQLi extension is not loaded"
];
$diagnostics['checks'][] = $check_mysqli;

// 10. Check Configuration
$check_config = [
    'name' => 'Configuration Status',
    'environment' => ENVIRONMENT,
    'site_url' => SITE_URL,
    'db_host' => DB_HOST,
    'db_user' => DB_USER,
    'status' => true,
    'message' => "✅ Configuration loaded"
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

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #001a4d 0%, #004d99 100%);
            min-height: 100vh;
            padding: 30px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .header h1 {
            color: #001a4d;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header p {
            color: #666;
            margin: 5px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            margin-top: 15px;
            font-size: 18px;
        }

        .status-badge.healthy {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.issues {
            background: #f8d7da;
            color: #721c24;
        }

        .diagnostics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
        }

        .check-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #ddd;
            transition: all 0.3s ease;
        }

        .check-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .check-card.passed {
            border-left-color: #28a745;
            background: #f0fff4;
        }

        .check-card.failed {
            border-left-color: #dc3545;
            background: #fff5f5;
        }

        .check-card.info {
            border-left-color: #17a2b8;
            background: #f0f8fb;
        }

        .check-card h3 {
            color: #001a4d;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
        }

        .check-card p {
            color: #555;
            margin: 8px 0;
            font-size: 14px;
            line-height: 1.6;
        }

        .icon {
            font-size: 20px;
        }

        .details {
            background: rgba(0, 0, 0, 0.05);
            padding: 12px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 13px;
            color: #666;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
        }

        .table-info {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 8px;
            font-size: 13px;
        }

        .table-info strong {
            color: #001a4d;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .footer {
            text-align: center;
            color: white;
            margin-top: 40px;
            padding: 20px;
        }

        .footer a {
            color: #00ffff;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .button-group {
            text-align: center;
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 0 10px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #001a4d;
            color: white;
        }

        .btn-primary:hover {
            background: #004d99;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #17a2b8;
            color: white;
        }

        .btn-secondary:hover {
            background: #138496;
            transform: translateY(-2px);
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
            <p><strong>Timestamp:</strong> <?php echo $diagnostics['timestamp']; ?></p>
            <p><strong>Environment:</strong> <?php echo ucfirst($diagnostics['environment']); ?></p>
            <p><strong>Site URL:</strong> <?php echo SITE_URL; ?></p>
            <div class="status-badge <?php echo $all_critical_passed ? 'healthy' : 'issues'; ?>">
                <?php echo $diagnostics['overall_status']; ?>
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
                            <div class="table-info">
                                <strong>Found:</strong>
                                <div><?php echo implode(', ', $check['tables_found']); ?></div>
                                <strong>Required:</strong>
                                <div><?php echo implode(', ', $check['tables_required']); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($check['count'])): ?>
                        <div class="details">
                            <strong>Count:</strong> <?php echo $check['count']; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($check['path'])): ?>
                        <div class="details">
                            <strong>Path:</strong> <?php echo $check['path']; ?><br>
                            <strong>Writable:</strong> <?php echo $check['writable'] ? 'Yes ✅' : 'No ❌'; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($check['version'])): ?>
                        <div class="details">
                            <?php echo $check['version']; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($check['site_url'])): ?>
                        <div class="details">
                            <div class="table-info">
                                <strong>Host:</strong>
                                <div><?php echo DB_HOST; ?></div>
                                <strong>User:</strong>
                                <div><?php echo DB_USER; ?></div>
                            </div>
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
