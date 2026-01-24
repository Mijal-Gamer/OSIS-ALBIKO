<?php
session_start();

// Check if logged in
$isLoggedIn = isset($_SESSION['login']) && $_SESSION['login'] === true;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Variables Test</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #08122a, #0d1b2a);
            color: white;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            color: #00e0ff;
            margin-bottom: 10px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #9be8ff;
            margin-bottom: 40px;
        }

        .status-box {
            background: rgba(8, 18, 42, 0.7);
            border: 2px solid rgba(0, 200, 255, 0.2);
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
        }

        .status-good {
            border-color: rgba(76, 175, 80, 0.5);
            background: rgba(76, 175, 80, 0.1);
        }

        .status-bad {
            border-color: rgba(255, 107, 107, 0.5);
            background: rgba(255, 107, 107, 0.1);
        }

        .status-icon {
            font-size: 3em;
            margin-bottom: 15px;
        }

        .status-text {
            font-size: 1.2em;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .status-description {
            color: #9be8ff;
            margin-bottom: 20px;
        }

        .session-data {
            background: rgba(0, 50, 100, 0.3);
            border: 2px solid rgba(0, 200, 255, 0.3);
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .session-item {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            margin-bottom: 10px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            border-left: 4px solid #00e0ff;
        }

        .session-key {
            color: #00e0ff;
            font-weight: 600;
        }

        .session-value {
            color: #4caf50;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }

        .session-type {
            color: #9be8ff;
            font-size: 0.85em;
            margin-top: 5px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, #00e0ff, #0077ff);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 200, 255, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #ff6b6b, #ff4757);
        }

        .info-box {
            background: rgba(0, 150, 200, 0.1);
            border-left: 4px solid #00e0ff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            color: #ccc;
        }

        .info-box strong {
            color: #00e0ff;
        }

        .code-box {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(0, 200, 255, 0.2);
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            color: #00ffff;
            overflow-x: auto;
        }

        .empty-sessions {
            padding: 20px;
            text-align: center;
            color: #ff6b6b;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(0, 200, 255, 0.2);
        }

        .time-info {
            color: #9be8ff;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>📊 Session Variables Test</h1>
                <p class="subtitle">Verify your login session</p>
            </div>
            <div class="time-info">
                <p><?php echo date('d/m/Y H:i:s'); ?></p>
                <p>Session ID: <?php echo session_id(); ?></p>
            </div>
        </div>

        <?php if ($isLoggedIn): ?>
            <!-- Login Status -->
            <div class="status-box status-good">
                <div class="status-icon">✅</div>
                <div class="status-text">You are logged in</div>
                <div class="status-description">
                    Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!
                </div>
                <div class="button-group">
                    <a href="dashboard.php" class="btn">📊 Go to Dashboard</a>
                    <a href="logout.php" class="btn btn-secondary">🚪 Logout</a>
                </div>
            </div>

            <!-- Session Data -->
            <div class="session-data">
                <h2 style="color: #00e0ff; margin-bottom: 20px;">📋 Session Variables</h2>
                
                <div class="session-item">
                    <div>
                        <div class="session-key">$_SESSION['login']</div>
                        <div class="session-type">Boolean - Login status</div>
                    </div>
                    <div class="session-value"><?php echo $_SESSION['login'] ? 'true' : 'false'; ?></div>
                </div>

                <div class="session-item">
                    <div>
                        <div class="session-key">$_SESSION['user_id']</div>
                        <div class="session-type">Integer - User ID from database</div>
                    </div>
                    <div class="session-value"><?php echo htmlspecialchars($_SESSION['user_id']); ?></div>
                </div>

                <div class="session-item">
                    <div>
                        <div class="session-key">$_SESSION['username']</div>
                        <div class="session-type">String - Username from database</div>
                    </div>
                    <div class="session-value"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                </div>

                <div class="session-item">
                    <div>
                        <div class="session-key">$_SESSION['role']</div>
                        <div class="session-type">String - User role (admin/user)</div>
                    </div>
                    <div class="session-value"><?php echo htmlspecialchars($_SESSION['role']); ?></div>
                </div>

                <div class="session-item">
                    <div>
                        <div class="session-key">$_SESSION['login_time']</div>
                        <div class="session-type">Integer - Login timestamp</div>
                    </div>
                    <div class="session-value">
                        <?php echo $_SESSION['login_time']; ?> 
                        <br><small>(<?php echo date('d/m/Y H:i:s', $_SESSION['login_time']); ?>)</small>
                    </div>
                </div>

                <div class="session-item">
                    <div>
                        <div class="session-key">session_id()</div>
                        <div class="session-type">String - PHP Session ID</div>
                    </div>
                    <div class="session-value" style="font-size: 0.8em;">
                        <?php echo htmlspecialchars(session_id()); ?>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="info-box">
                <strong>💡 How to use in PHP:</strong>
                <div class="code-box">
&lt;?php<br>
// Check if user is logged in<br>
if (isset($_SESSION['login']) &amp;&amp; $_SESSION['login'] === true) {<br>
&nbsp;&nbsp;$username = $_SESSION['username'];<br>
&nbsp;&nbsp;$role = $_SESSION['role'];<br>
&nbsp;&nbsp;// Allow access to admin pages<br>
} else {<br>
&nbsp;&nbsp;header("Location: login.php");<br>
&nbsp;&nbsp;exit;<br>
}<br>
?&gt;
                </div>
            </div>

            <!-- Full Session Dump -->
            <div class="session-data">
                <h2 style="color: #00e0ff; margin-bottom: 20px;">🔍 Full $_SESSION Array</h2>
                <div class="code-box">
                    <pre><?php var_dump($_SESSION); ?></pre>
                </div>
            </div>

        <?php else: ?>
            <!-- Not Logged In -->
            <div class="status-box status-bad">
                <div class="status-icon">❌</div>
                <div class="status-text">You are NOT logged in</div>
                <div class="status-description">
                    You need to login first to view session variables.
                </div>
                <div class="button-group">
                    <a href="login.php" class="btn">🔑 Go to Login</a>
                </div>
            </div>

            <div class="session-data">
                <div class="empty-sessions">
                    <p>❌ No session variables found</p>
                    <p style="margin-top: 10px; font-size: 0.9em;">Login first, then come back to this page to see session data.</p>
                </div>
            </div>

            <div class="info-box">
                <strong>ℹ️ To test session:</strong>
                <ol style="margin: 10px 0 0 20px;">
                    <li>Click "Go to Login" button above</li>
                    <li>Login with test credentials (e.g., admin / admin123)</li>
                    <li>You'll be redirected to dashboard.php</li>
                    <li>Come back to this page to see session variables</li>
                </ol>
            </div>
        <?php endif; ?>

        <!-- Footer -->
        <div style="text-align: center; margin-top: 50px; padding-top: 20px; border-top: 1px solid rgba(0,200,255,0.2); color: #9be8ff;">
            <p><strong>Login System Test Page</strong> - January 2026</p>
            <p style="margin-top: 10px; color: #7f8c8d;">Session testing for development purposes</p>
        </div>
    </div>
</body>
</html>
