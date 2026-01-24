# 📋 SETUP OSIS ASTAMAYANA DI HOSTING

## ✅ Checklist Deployment

### 1️⃣ GitHub Repository
- ✅ Repository: https://github.com/Mijal-Gamer/OSIS-ALBIKO
- ✅ Sudah push semua code dan configuration

### 2️⃣ Informasi yang Dibutuhkan dari Hosting

Hubungi hosting support atau cek email hosting untuk mendapat:

```
🔐 Database Information:
   - Database Host: ___________________
   - Database User: ___________________
   - Database Password: _______________
   
🌐 FTP/SFTP Information:
   - FTP Host: _______________________
   - FTP Username: ____________________
   - FTP Password: ____________________
   - FTP Port: ________________________
   
📁 Directory Information:
   - Public HTML Path: _________________
   - (Usually: /public_html/ atau /www/)
```

### 3️⃣ Create Databases di Hosting

**Via cPanel > phpMyAdmin:**

1. Login ke cPanel
2. Buka phpMyAdmin
3. Klik "New"
4. Buat Database: `osis_auth`
   ```sql
   CREATE DATABASE osis_auth;
   ```
5. Buat Database: `osis`
   ```sql
   CREATE DATABASE osis;
   ```

### 4️⃣ Create Database User

**Via cPanel > MySQL Databases:**

1. Buat User:
   - Username: `osis_user`
   - Password: [Generate Strong Password - SIMPAN BAIK-BAIK!]
   
2. Assign User ke Database:
   - Assign `osis_user` ke `osis_auth` - ALL PRIVILEGES
   - Assign `osis_user` ke `osis` - ALL PRIVILEGES

### 5️⃣ Import Database Structure

**Langkah-langkah:**

1. Buka phpMyAdmin di cPanel
2. Login
3. Di sidebar kiri, klik database `osis_auth`
4. Klik tab "Import"
5. Upload file SQL dari repo atau create tables manual:

**Create Tables - osis_auth database:**

```sql
-- Table users
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'admin',
  token VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table struktur_organisasi
CREATE TABLE struktur_organisasi (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tipe VARCHAR(50) NOT NULL,
  kategori VARCHAR(100) NOT NULL,
  nama VARCHAR(255) NOT NULL,
  posisi VARCHAR(100) NOT NULL,
  urutan INT DEFAULT 0,
  dibuat_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  diupdate_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table galeri
CREATE TABLE galeri (
  id INT PRIMARY KEY AUTO_INCREMENT,
  judul VARCHAR(255) NOT NULL,
  deskripsi TEXT,
  foto LONGTEXT NOT NULL,
  tipe_file VARCHAR(50),
  ukuran_file INT,
  dibuat_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Create Default Admin User - Run this query:**

```sql
-- Default admin user - CHANGE PASSWORD!
INSERT INTO users (username, password, role) 
VALUES ('admin', SHA2('admin123', 256), 'admin');
-- Update password after first login!
```

### 6️⃣ Update Configuration File

**Download dari GitHub:**
```
https://github.com/Mijal-Gamer/OSIS-ALBIKO/blob/main/config.php
```

**Edit config.php dengan credentials hosting Anda:**

```php
// Production config (around line 11-14)
define('DB_HOST', 'localhost'); // dari hosting info
define('DB_USER', 'osis_user'); // user yang dibuat di step 4
define('DB_PASS', 'password_dari_step_4'); // password yang di-generate
```

### 7️⃣ Upload Files ke Hosting

**Option A: Menggunakan FTP (Easiest)**

1. Download FileZilla atau FTP Client lainnya
2. Credentials dari hosting:
   - Host: FTP Host
   - Username: FTP Username
   - Password: FTP Password
   - Port: 21 (atau yang disarankan)
3. Connect ke FTP
4. Navigate ke `/public_html/`
5. Upload semua file dari repo OSIS-ALBIKO
6. Folder yang important:
   - assets/ (CSS, JS, images)
   - uploads/ (untuk gallery)
   - Semua .php files

**Option B: Menggunakan Git (Jika hosting support SSH)**

```bash
# SSH ke hosting
ssh username@osis-astamayana.space

# Navigate ke public_html
cd public_html

# Clone repo
git clone https://github.com/Mijal-Gamer/OSIS-ALBIKO.git

# Update config.php dengan hosting credentials
nano OSIS-ALBIKO/config.php
# Edit dan save (Ctrl+X, Y, Enter)

# Set permissions
chmod 755 OSIS-ALBIKO/uploads/
chmod 755 OSIS-ALBIKO/assets/
```

### 8️⃣ Set Folder Permissions

**Via FTP Client (FileZilla):**

1. Right-click folder `uploads/` → Properties
2. Set permissions: `755`
3. Do same untuk `assets/` folder

**Via SSH:**
```bash
chmod 755 uploads/
chmod 755 assets/
```

### 9️⃣ Create .htaccess (if using Apache)

**File: .htaccess**
```apache
# Enable HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Remove index.php from URL (optional)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?/$1 [L]
```

### 🔟 Test Website

1. Buka https://osis-astamayana.space/
2. Verify semua elemen loading:
   - Logo muncul
   - Gallery section muncul
   - Struktur organisasi muncul
3. Test login:
   - Username: admin
   - Password: admin123 (CHANGE THIS!)
4. Test functionality:
   - Buka Edit Konten
   - Test upload gallery
   - Test edit struktur organisasi

### 1️⃣1️⃣ Set SSL Certificate

Domain https://osis-astamayana.space sudah harus punya SSL.

Di cPanel:
1. Buka AutoSSL
2. Atau buka "Let's Encrypt SSL"
3. Install SSL untuk domain

### 1️⃣2️⃣ Change Admin Password

⚠️ **PENTING! JANGAN LUPA!**

1. Login ke admin: https://osis-astamayana.space/dashboard.php
2. Kalau ada "Change Password" option, update password
3. Atau direct update di database:
   ```sql
   UPDATE users SET password = SHA2('new_secure_password', 256) 
   WHERE username = 'admin';
   ```

### 1️⃣3️⃣ Backup Database Script

File sudah tersedia: `backup-database.php`

Untuk backup otomatis, cron job:
```bash
# Every day at 2 AM
0 2 * * * curl https://osis-astamayana.space/backup-database.php > /home/backup/osis-backup-$(date +\%Y-\%m-\%d).sql
```

---

## 🆘 Troubleshooting

### "Connection failed"
❌ Problem: Database tidak bisa connect
✅ Solution:
   - Verify DB_HOST, DB_USER, DB_PASS di config.php
   - Check database ada di hosting
   - Verify user privileges

### "Permission denied" uploads
❌ Problem: Tidak bisa upload gallery
✅ Solution:
   - Set uploads/ folder permissions ke 755
   - Verify uploads/ folder exists
   - Check disk space

### Logo/Images tidak muncul
❌ Problem: Assets tidak loading
✅ Solution:
   - Check SITE_URL di config.php = https://osis-astamayana.space/
   - Verify assets/ folder uploaded completely
   - Check browser console untuk 404 errors

### Database tables tidak ada
❌ Problem: Error "table not found"
✅ Solution:
   - Verify semua CREATE TABLE queries sudah execute
   - Check phpMyAdmin untuk list tables
   - Re-import database jika perlu

---

## 📞 Contact Support

Jika ada masalah:
1. Check error logs di cPanel
2. Check server phpMyAdmin
3. Test localhost version untuk verify code
4. Contact hosting support

**Hosting Support:**
- Email: [hosting email]
- Ticket: [support system]

---

## ✨ Setup Complete!

Setelah semua step selesai, OSIS Astamayana production website sudah live! 🎉

- Website: https://osis-astamayana.space/
- Repository: https://github.com/Mijal-Gamer/OSIS-ALBIKO
- Admin: https://osis-astamayana.space/dashboard.php
