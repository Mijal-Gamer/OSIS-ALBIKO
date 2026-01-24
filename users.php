<?php
// require 'auth-check.php';
require 'helpers.php';

// Get all users
$users_result = queryAuth("SELECT id, username, email, full_name, role, is_active, created_at FROM users");
$users = fetchAll($users_result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Users - OSIS Astamayana</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin-style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #08122a, #020409, #0d1b2a);
            color: white;
            min-height: 100vh;
        }

        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(0, 15, 30, 0.96);
            backdrop-filter: blur(12px);
            padding: 12px 30px;
            z-index: 100;
            border-bottom: 1px solid rgba(0, 200, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h2 {
            color: #00e0ff;
            font-size: 1.3rem;
        }

        .nav-links {
            display: flex;
            gap: 15px;
        }

        .nav-links a {
            color: #cceeff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            background: rgba(0, 200, 255, 0.1);
            color: #00ffff;
        }

        .logout-btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b) !important;
            color: white !important;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 100px 30px 50px;
        }

        h1 {
            color: #00e0ff;
            margin-bottom: 30px;
            text-shadow: 0 0 15px rgba(0, 200, 255, 0.3);
        }

        .btn-add {
            display: inline-block;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            text-decoration: none;
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 204, 113, 0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 10px;
            overflow: hidden;
        }

        table th {
            background: rgba(0, 200, 255, 0.1);
            color: #00e0ff;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid rgba(0, 200, 255, 0.2);
        }

        table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(0, 200, 255, 0.1);
        }

        table tr:hover {
            background: rgba(0, 200, 255, 0.05);
        }

        .status-active {
            color: #2ecc71;
        }

        .status-inactive {
            color: #e74c3c;
        }

        .role-admin {
            background: rgba(52, 152, 219, 0.2);
            color: #3498db;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
        }

        .role-user {
            background: rgba(155, 89, 182, 0.2);
            color: #9b59b6;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85em;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: #3498db;
            color: white;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
        }

        .btn-sm:hover {
            transform: scale(1.05);
        }

        footer {
            text-align: center;
            padding: 30px;
            color: #9be8ff;
            border-top: 1px solid rgba(0, 200, 255, 0.1);
        }

        @media (max-width: 768px) {
            .container {
                padding: 80px 15px 50px;
            }

            table {
                font-size: 0.9em;
            }

            table th, table td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h2><i class="ri-user-3-line"></i> Manajemen Users</h2>
        <div class="nav-links">
            <a href="dashboard.php"><i class="ri-dashboard-line"></i> Dashboard</a>
            <a href="logout.php" class="logout-btn"><i class="ri-logout-box-line"></i> Logout</a>
        </div>
    </header>

    <div class="container">
        <h1>👥 Manajemen User Administrator</h1>
        
        <a href="add-user.php" class="btn-add"><i class="ri-user-add-line"></i> Tambah User Baru</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Nama Lengkap</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($user['full_name'] ?? '-'); ?></td>
                            <td>
                                <span class="role-<?php echo $user['role']; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td class="status-<?php echo $user['is_active'] ? 'active' : 'inactive'; ?>">
                                <?php echo $user['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                            </td>
                            <td><?php echo formatDate($user['created_at']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-sm btn-edit" onclick="editUser(<?php echo $user['id']; ?>)">
                                        <i class="ri-edit-line"></i> Edit
                                    </button>
                                    <button class="btn-sm btn-delete" onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo $user['username']; ?>')">
                                        <i class="ri-delete-bin-line"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: #7f8c8d; padding: 30px;">
                            Tidak ada user ditemukan
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>© 2025 OSIS Astamayana - Sistem Manajemen User</p>
    </footer>

    <script>
        function editUser(id) {
            window.location.href = `edit-user.php?id=${id}`;
        }

        function deleteUser(id, username) {
            if (confirm(`Apakah Anda yakin ingin menghapus user "${username}"?`)) {
                // Implement delete via AJAX
                fetch('delete-user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('User berhasil dihapus');
                        location.reload();
                    } else {
                        alert('Gagal menghapus user: ' + data.message);
                    }
                });
            }
        }
    </script>
</body>
</html>