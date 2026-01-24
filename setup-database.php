<?php
/**
 * Setup Database OSIS_AUTH
 * File ini untuk setup database dan insert sample user
 * Jalankan file ini di browser: http://localhost/OSIS-ALBIKO/setup-database.php
 */

$host = 'localhost';
$user = 'root';
$password = '';

// Connect tanpa database
$conn = mysqli_connect($host, $user, $password);

if (!$conn) {
    die("❌ Connection failed: " . mysqli_connect_error());
}

echo "<h2>🔧 Setup Database OSIS_AUTH</h2>";
echo "<pre style='background: #f4f4f4; padding: 20px; border-radius: 5px;'>";

// 1. Create Database
$db_name = 'osis_auth';
$sql = "CREATE DATABASE IF NOT EXISTS $db_name";

if (mysqli_query($conn, $sql)) {
    echo "✅ Database '$db_name' created successfully\n";
} else {
    echo "❌ Error creating database: " . mysqli_error($conn) . "\n";
    exit;
}

// 2. Connect ke database
$conn = mysqli_connect($host, $user, $password, $db_name);
if (!$conn) {
    die("❌ Connection to database failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

// 3. Create Table Users
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username)
)";

if (mysqli_query($conn, $sql)) {
    echo "✅ Table 'users' created/verified successfully\n";
} else {
    echo "❌ Error creating table: " . mysqli_error($conn) . "\n";
    exit;
}

// 4. Clear existing test users
mysqli_query($conn, "DELETE FROM users WHERE username IN ('admin', 'user1', 'user2')");
echo "✅ Cleared existing test users\n";

// 5. Insert Test Users
$users = [
    ['admin', 'admin123', 'admin'],
    ['user1', 'password123', 'user'],
    ['user2', 'password456', 'user']
];

foreach ($users as $userdata) {
    $username = $userdata[0];
    $password = $userdata[1];
    $role = $userdata[2];
    
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $username, $password, $role);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "✅ User '$username' created (Password: '$password')\n";
    } else {
        echo "❌ Error creating user '$username': " . mysqli_error($conn) . "\n";
    }
    mysqli_stmt_close($stmt);
}

// 6. Verify data
echo "\n📊 Database Contents:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$result = mysqli_query($conn, "SELECT id, username, password, role, created_at FROM users");

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row['id'] . "\n";
        echo "Username: " . $row['username'] . "\n";
        echo "Password: " . $row['password'] . "\n";
        echo "Role: " . $row['role'] . "\n";
        echo "Created: " . $row['created_at'] . "\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
} else {
    echo "❌ No users found\n";
}

echo "</pre>";

echo "<div style='margin-top: 30px; padding: 20px; background: #e8f5e9; border-radius: 5px;'>";
echo "<h3>✅ Setup Completed Successfully!</h3>";
echo "<p>You can now test login with these credentials:</p>";
echo "<table style='border-collapse: collapse; margin-top: 10px;'>";
echo "<tr style='background: #c8e6c9;'><th style='padding: 8px; border: 1px solid #4caf50;'>Username</th><th style='padding: 8px; border: 1px solid #4caf50;'>Password</th><th style='padding: 8px; border: 1px solid #4caf50;'>Role</th></tr>";
foreach ($users as $userdata) {
    echo "<tr>";
    echo "<td style='padding: 8px; border: 1px solid #4caf50;'>" . $userdata[0] . "</td>";
    echo "<td style='padding: 8px; border: 1px solid #4caf50;'>" . $userdata[1] . "</td>";
    echo "<td style='padding: 8px; border: 1px solid #4caf50;'>" . $userdata[2] . "</td>";
    echo "</tr>";
}
echo "</table>";
echo "<p style='margin-top: 15px;'><strong>🔐 Try Login:</strong> <a href='login.php' style='color: blue; text-decoration: underline;'>Click here to go to login page</a></p>";
echo "</div>";

mysqli_close($conn);
?>
