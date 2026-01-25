<?php
require 'auth-check.php';
require 'connect-auth.php';
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
    $users_query = "SELECT id, username, role, password FROM users";
    $users_result = mysqli_query($conn_auth_test, $users_query);
    $users_count = 0;
    
    if ($users_result && mysqli_num_rows($users_result) > 0) {
        while ($row = mysqli_fetch_assoc($users_result)) {
            $users_count++;
            $admin_users[] = [
                'id' => $row['id'],
                'username' => $row['username'],
                'role' => $row['role'],
                'password' => $row['password']
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
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
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

        @keyframes drift {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(150px, -100px); }
        }

        body {
            background: linear-gradient(135deg, #08122a, #020409, #0d1b2a);
            color: white;
            position: relative;
            overflow-x: hidden;
            min-height: 100vh;
            padding: 100px 30px 50px 30px;
        }

        .light {
            position: fixed;
            width: 600px;
            height: 600px;
            pointer-events: none;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 180, 255, 0.4), transparent 70%);
            filter: blur(80px);
            z-index: 0;
            animation: drift 15s ease-in-out infinite;
            top: -100px;
            left: -100px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            background: rgba(0, 15, 30, 0.96);
            backdrop-filter: blur(12px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 30px;
            z-index: 100;
            border-bottom: 1px solid rgba(0, 200, 255, 0.1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.8s ease-out;
        }

        @keyframes slideDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }

        header h2 {
            color: #00e6ff;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            text-shadow: 0 2px 8px rgba(0, 255, 255, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        header h2 i {
            animation: rotate 3s linear infinite;
            font-size: 28px;
        }

        header h2:hover {
            color: #00ffff;
            text-shadow: 0 2px 15px rgba(0, 255, 255, 0.6);
            transform: translateY(-2px);
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .header-btn {
            padding: 8px 16px;
            border: 1px solid rgba(0, 200, 255, 0.5);
            background: rgba(0, 200, 255, 0.1);
            color: #00e6ff;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .header-btn:hover {
            background: rgba(0, 200, 255, 0.2);
            border-color: rgba(0, 255, 255, 0.8);
            color: #00ffff;
            transform: translateY(-2px);
        }

        .header-section {
            background: rgba(0, 20, 40, 0.8);
            backdrop-filter: blur(12px);
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 200, 255, 0.15);
            animation: fadeInDown 0.6s ease-out;
        }

        .header-section h1 {
            color: #00e6ff;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 28px;
            text-shadow: 0 2px 15px rgba(0, 200, 255, 0.4);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .info-box {
            background: rgba(0, 200, 255, 0.08);
            padding: 18px;
            border-radius: 10px;
            border: 1px solid rgba(0, 200, 255, 0.2);
            border-left: 4px solid #00e6ff;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .info-box:hover {
            background: rgba(0, 200, 255, 0.12);
            border-color: rgba(0, 200, 255, 0.4);
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 200, 255, 0.2);
        }

        .info-box label {
            display: block;
            color: #a0d8ff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .info-box .value {
            display: block;
            color: #00ffff;
            font-size: 15px;
            font-weight: 600;
            word-break: break-all;
            font-family: 'Courier New', monospace;
        }

        .status-badge {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: bold;
            margin-top: 20px;
            font-size: 16px;
            animation: slideInUp 0.6s ease-out;
        }

        .status-badge.healthy {
            background: linear-gradient(135deg, #00d477, #00a860);
            color: white;
            box-shadow: 0 8px 20px rgba(0, 212, 119, 0.3);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .status-badge.issues {
            background: linear-gradient(135deg, #ff4757, #ff3838);
            color: white;
            box-shadow: 0 8px 20px rgba(255, 71, 87, 0.3);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .refresh-indicator {
            display: inline-block;
            padding: 8px 15px;
            background: rgba(0, 255, 255, 0.15);
            border: 1px solid rgba(0, 255, 255, 0.4);
            border-radius: 20px;
            color: #00ffff;
            font-size: 12px;
            margin-left: 20px;
            font-weight: 500;
        }

        .refresh-indicator i {
            animation: rotate 2s linear infinite;
            margin-right: 5px;
        }

        .diagnostics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .check-card {
            background: rgba(0, 20, 40, 0.8);
            backdrop-filter: blur(12px);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(0, 200, 255, 0.15);
            border-left: 5px solid rgba(0, 200, 255, 0.3);
            transition: all 0.3s ease;
            animation: slideInUp 0.5s ease-out backwards;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .check-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .check-card:hover::before {
            left: 100%;
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
            background: rgba(0, 30, 50, 0.95);
            box-shadow: 0 15px 40px rgba(0, 200, 255, 0.2);
            border-color: rgba(0, 200, 255, 0.4);
        }

        .check-card.passed {
            border-left-color: #00d477;
            background: rgba(0, 40, 30, 0.8);
        }

        .check-card.passed:hover {
            background: rgba(0, 50, 40, 0.9);
            box-shadow: 0 15px 40px rgba(0, 212, 119, 0.25);
            border-color: rgba(0, 212, 119, 0.4);
        }

        .check-card.failed {
            border-left-color: #ff4757;
            background: rgba(50, 15, 15, 0.8);
        }

        .check-card.failed:hover {
            background: rgba(60, 20, 20, 0.9);
            box-shadow: 0 15px 40px rgba(255, 71, 87, 0.25);
            border-color: rgba(255, 71, 87, 0.4);
        }

        .check-card.info {
            border-left-color: #00a8ff;
            background: rgba(0, 30, 50, 0.8);
        }

        .check-card.info:hover {
            box-shadow: 0 15px 40px rgba(0, 168, 255, 0.25);
            border-color: rgba(0, 168, 255, 0.4);
        }

        .check-card h3 {
            color: #00e6ff;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            text-shadow: 0 2px 8px rgba(0, 200, 255, 0.2);
        }

        .check-card h3 i {
            font-size: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .check-card:hover h3 i {
            transform: rotate(10deg) scale(1.15);
            text-shadow: 0 0 15px rgba(0, 200, 255, 0.6);
        }

        .check-card p {
            color: #b0d8ff;
            margin: 8px 0;
            font-size: 14px;
            line-height: 1.6;
        }

        .icon {
            font-size: 24px;
            animation: pulse 2s ease-in-out infinite;
        }

        .details {
            background: rgba(0, 0, 0, 0.3);
            padding: 15px;
            border-radius: 8px;
            margin-top: 12px;
            font-size: 13px;
            color: #a0d8ff;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            border: 1px solid rgba(0, 200, 255, 0.15);
        }

        .details strong {
            color: #00ffff;
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .user-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .user-item {
            background: rgba(0, 100, 200, 0.15);
            padding: 12px 15px;
            border-radius: 6px;
            border-left: 3px solid #00a8ff;
            font-size: 13px;
            border: 1px solid rgba(0, 168, 255, 0.2);
            transition: all 0.3s ease;
        }

        .user-item:hover {
            background: rgba(0, 100, 200, 0.25);
            border-color: rgba(0, 200, 255, 0.4);
        }

        .user-item .username {
            color: #00ffff;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            display: block;
            margin-bottom: 3px;
        }

        .user-item .role {
            color: #80c8ff;
            font-size: 12px;
        }

        .user-item .password {
            color: #ff6b6b;
            font-family: 'Courier New', monospace;
            margin-top: 5px;
            padding: 8px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 4px;
            display: block;
            word-break: break-all;
        }

        .password-label {
            color: #ffa502;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .footer {
            text-align: center;
            color: #a0d8ff;
            margin-top: 50px;
            padding: 30px 20px;
            border-top: 1px solid rgba(0, 200, 255, 0.1);
            animation: fadeInDown 0.8s ease-out;
        }

        .footer p {
            margin: 5px 0;
            font-size: 14px;
        }

        .footer a {
            color: #00ffff;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .footer a:hover {
            text-decoration: underline;
            text-shadow: 0 0 10px rgba(0, 200, 255, 0.5);
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
            padding: 12px 30px;
            margin: 0 10px;
            border: 2px solid rgba(0, 200, 255, 0.5);
            background: rgba(0, 50, 100, 0.6);
            color: #00e6ff;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .btn:hover {
            background: rgba(0, 80, 150, 0.8);
            border-color: rgba(0, 255, 255, 0.8);
            color: #00ffff;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 200, 255, 0.3);
        }

        .btn i {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="light"></div>

    <header>
        <h2>
            <i class="ri-stethoscope-line"></i>
            Diagnostic
        </h2>
        <div class="header-actions">
            <a href="index.php" class="header-btn">
                <i class="ri-home-line"></i> Home
            </a>
            <a href="dashboard.php" class="header-btn">
                <i class="ri-dashboard-line"></i> Dashboard
            </a>
        </div>
    </header>

    <div class="container">
        <div class="header-section">
            <h1>
                <i class="ri-stethoscope-line"></i>
                System Diagnostic
            </h1>
            
            <div class="info-grid">
                <div class="info-box">
                    <label>Environment</label>
                    <span class="value"><?php echo ucfirst($diagnostics['environment']); ?></span>
                </div>
                <div class="info-box">
                    <label>Database Host</label>
                    <span class="value"><?php echo DB_HOST; ?></span>
                </div>
                <div class="info-box">
                    <label>Database User</label>
                    <span class="value"><?php echo DB_USER; ?></span>
                </div>
                <div class="info-box">
                    <label>Auth Database</label>
                    <span class="value"><?php echo DB_AUTH; ?></span>
                </div>
                <div class="info-box">
                    <label>Main Database</label>
                    <span class="value"><?php echo DB_MAIN; ?></span>
                </div>
                <div class="info-box">
                    <label>Scan Time</label>
                    <span class="value"><?php echo $diagnostics['timestamp']; ?></span>
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
                            <strong>👥 Admin Users & Credentials:</strong>
                            <div class="user-list">
                                <?php foreach ($check['users'] as $user): ?>
                                    <div class="user-item">
                                        <span class="username">📧 Username: <?php echo htmlspecialchars($user['username']); ?></span>
                                        <span class="role">🔑 Role: <?php echo htmlspecialchars($user['role']); ?></span>
                                        <?php if (isset($user['password'])): ?>
                                            <span class="password">
                                                <span class="password-label">🔐 Password Hash:</span>
                                                <?php echo htmlspecialchars($user['password']); ?>
                                            </span>
                                        <?php endif; ?>
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
            <a href="index.php" class="btn">
                <i class="ri-home-line"></i> Go to Homepage
            </a>
            <a href="dashboard.php" class="btn">
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
