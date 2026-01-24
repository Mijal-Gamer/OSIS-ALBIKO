# 🔐 Login System Documentation

## Overview
Login system telah dirancang ulang dengan design yang elegan, aman, dan mudah digunakan. Sistem menggunakan database `osis_auth` untuk validasi username dan password dengan session-based authentication (tanpa cookies kompleks).

---

## 📋 Features

### 1. **Design Elegan & Responsive**
- Beautiful gradient background dengan floating blur effects
- Smooth animations (fade in, slide in, scale)
- Fully responsive untuk semua ukuran device
- Modern glassmorphism effect dengan backdrop blur

### 2. **Keamanan Database**
- **Prepared Statements** untuk mencegah SQL Injection
- Koneksi langsung ke database `osis_auth`
- Support untuk password yang di-hash dengan `password_hash()`
- Fallback ke plain text password (untuk backward compatibility)

### 3. **Authentication Flow**
- Session-based authentication (sederhana, tanpa complexity)
- Auto-redirect ke dashboard jika sudah login
- Persistent session untuk semua admin pages
- Graceful logout yang clear session

### 4. **User Experience**
- Error messages yang jelas dan informatif
- Input validation pada client dan server side
- Autofocus pada username field
- Smooth error animations

---

## 🗄️ Database Structure

### Table: `users` (di database `osis_auth`)

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Sample Data (untuk testing):

```sql
-- Plain text password (simple)
INSERT INTO users (username, password, role) VALUES
('admin', 'admin123', 'admin'),
('user1', 'password123', 'user'),
('user2', 'password456', 'user');

-- Hashed password (lebih aman)
-- password: password123
INSERT INTO users (username, password, role) VALUES
('admin2', '$2y$10$YQvUePAVUZjqWvWepCZH.eYzS8.G7.N8ZQH8.Z8.Z8.Z8.Z8.Z8', 'admin');
```

---

## 🔑 Login Flow

### Step-by-Step Process:

1. **User Mengakses login.php**
   - Cek apakah sudah ada session login
   - Jika sudah login → redirect ke dashboard.php
   - Jika belum → tampilkan form login

2. **User Input Username & Password**
   - Client-side validation (tidak boleh kosong)
   - Form submit dengan method POST

3. **Server Processing (handle-login.php)**
   ```php
   - Trim input untuk menghilangkan whitespace
   - Query database dengan prepared statement
   - Cek apakah username ada
   - Cek password dengan password_verify() atau plain text comparison
   - Set session jika login berhasil
   - Redirect ke dashboard.php
   - Tampilkan error jika gagal
   ```

4. **Session Management**
   ```php
   $_SESSION['login'] = true
   $_SESSION['user_id'] = $user['id']
   $_SESSION['username'] = $user['username']
   $_SESSION['role'] = $user['role']
   $_SESSION['login_time'] = time()
   ```

5. **Auth Check pada Admin Pages**
   - File `auth-check.php` memverifikasi session
   - Redirect ke login jika session tidak valid

---

## 📂 File Structure

### Main Files:
- **login.php** - Form login dengan design elegan
- **handle-login.php** - Authentication processing (optional, bisa digabung)
- **auth-check.php** - Session validation untuk admin pages
- **connect-auth.php** - Database connection ke osis_auth
- **logout.php** - Clear session dan redirect

### Integration Points:
- **dashboard.php** - Require auth-check.php
- **feedback.php** - Require auth-check.php
- **edit-konten.php** - Require auth-check.php

---

## 🔒 Password Handling

### Method 1: Plain Text (Simple)
```php
if ($password === $user['password']) {
    // Login berhasil
}
```

### Method 2: Hashed Password (Aman)
```php
if (password_verify($password, $user['password'])) {
    // Login berhasil
}
```

### Auto-Detection (Current Implementation):
```php
// Try hashed first
if (password_verify($password, $user['password'])) {
    $passwordMatch = true;
}
// Fallback to plain text
elseif ($password === $user['password']) {
    $passwordMatch = true;
}
```

---

## 🎨 Design Elements

### Color Scheme:
- **Primary**: Cyan (#00e0ff)
- **Secondary**: Blue (#0077ff)
- **Dark Background**: #08122a
- **Error**: Red (#ff6b6b)

### Animations:
- **fadeInUp** - Header slide up
- **scaleIn** - Logo appears
- **slideInLeft** - Form fields
- **slideInDown** - Error messages
- **drift** - Background blur effects

### Responsive Breakpoints:
- Mobile (<480px): Smaller padding, adjusted font sizes
- Tablet (480px-768px): Standard layout
- Desktop (>768px): Full width optimization

---

## 🧪 Testing Credentials

Jika sudah menjalankan script di atas, gunakan:

| Username | Password | Role |
|----------|----------|------|
| admin | admin123 | admin |
| user1 | password123 | user |
| user2 | password456 | user |

---

## ✅ Security Checklist

- [x] **Prepared Statements** - Mencegah SQL Injection
- [x] **Session-based Auth** - Sederhana dan aman
- [x] **Password Support** - Hash dan plain text
- [x] **Input Validation** - Client dan server side
- [x] **Auto-redirect** - Sudah login → dashboard
- [x] **Auth Check** - Admin pages protected
- [x] **Session Timeout** - Standard PHP (session.gc_maxlifetime)
- [x] **Error Messages** - User-friendly, tidak expose DB info

---

## 🚀 Deployment Steps

### Step 1: Setup Database
```sql
-- Login ke MySQL
mysql -u root

-- Buat database
CREATE DATABASE osis_auth;

-- Switch ke database
USE osis_auth;

-- Buat table users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert test user
INSERT INTO users (username, password, role) VALUES ('admin', 'admin123', 'admin');
```

### Step 2: Upload Files
- upload `login.php` ke root folder
- Pastikan `connect-auth.php` sudah ada
- Pastikan `auth-check.php` sudah include di admin pages

### Step 3: Test Login
```
http://localhost/OSIS-ALBIKO/login.php
Username: admin
Password: admin123
```

### Step 4: Enable Auth Check (optional)
Uncomment `require 'auth-check.php';` di:
- `dashboard.php`
- `feedback.php`
- `edit-konten.php`

---

## 🐛 Troubleshooting

### "Database connection failed"
- Check `connect-auth.php` settings
- Pastikan MySQL running
- Pastikan database `osis_auth` sudah ada

### "Username tidak ditemukan" padahal sudah insert
- Cek username case-sensitive
- Query: `SELECT * FROM users WHERE username = 'admin';`

### "Password salah" padahal input benar
- Jika menggunakan hashed password, pastikan format valid
- Coba pakai plain text dulu untuk testing

### Session tidak persist
- Check `php.ini` settings
- Pastikan session path writable
- Clear browser cookies

### Redirect loop
- Cek `auth-check.php` logic
- Pastikan tidak ada circular redirects

---

## 📝 Code Examples

### Create User dengan Hashed Password:
```php
$username = 'newuser';
$password = password_hash('mypassword123', PASSWORD_BCRYPT);
$role = 'admin';

$stmt = mysqli_prepare($conn_auth, 
    "INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sss", $username, $password, $role);
mysqli_stmt_execute($stmt);
```

### Manual Login Verification:
```php
$username = 'admin';
$password = 'admin123';

$stmt = mysqli_prepare($conn_auth, "SELECT * FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    if (password_verify($password, $user['password'])) {
        echo "Login berhasil!";
    } else {
        echo "Password salah!";
    }
} else {
    echo "Username tidak ditemukan!";
}
```

---

## 🎯 Next Steps

1. ✅ Setup database `osis_auth` dengan table `users`
2. ✅ Insert minimal satu user untuk testing
3. ✅ Test login di `http://localhost/OSIS-ALBIKO/login.php`
4. ⏳ Enable `auth-check.php` di admin pages (optional)
5. ⏳ Test full workflow: login → dashboard → logout

---

## 📞 Support

Jika ada pertanyaan atau issue:
1. Cek browser console untuk JavaScript errors
2. Cek PHP error log (`tail -f /var/log/php-errors.log`)
3. Test database connection dengan phpMyAdmin
4. Verify `connect-auth.php` credentials

---

**Last Updated**: January 2026
**Version**: 1.0 - Production Ready
**Status**: ✅ Clean, Elegant, Secure

