# OSIS Astamayana - Deployment Guide

## 🚀 Setup untuk Production (osis-astamayana.space)

### 1. GitHub Repository
Repository sudah tersimpan di: https://github.com/Mijal-Gamer/OSIS-ALBIKO

Status: ✅ Connected and synced

### 2. Database Setup di Hosting

#### Step 1: Create Databases
Buka cPanel atau hosting control panel, buka phpMyAdmin dan buat 2 database:
```sql
-- Database 1 (Authentication)
CREATE DATABASE osis_auth;

-- Database 2 (Main Content)
CREATE DATABASE osis;
```

#### Step 2: Import Database Structure
Download file [database-backup.sql](./database-backup.sql) dari repo

Di phpMyAdmin:
1. Pilih database `osis_auth`
2. Klik tab "Import"
3. Upload file `database-backup.sql`
4. Klik "Import"

Ulangi untuk database `osis`

#### Step 3: Create Database User
Di cPanel > MySQL Databases:
```
Username: osis_user
Password: [Generate Strong Password]
Database: osis_auth, osis
Privileges: ALL
```

### 3. Update Configuration File

Edit [config.php](./config.php) dengan informasi hosting Anda:

```php
// PRODUCTION CONFIG
define('DB_HOST', 'localhost'); // atau IP server hosting
define('DB_USER', 'osis_user'); // sesuai user yang dibuat
define('DB_PASS', 'your_secure_password'); // password dari step 3
```

### 4. File Transfer ke Hosting

Gunakan FTP/SFTP untuk upload semua files ke `/public_html/`

Via Terminal (jika hosting mendukung SSH):
```bash
git clone https://github.com/Mijal-Gamer/OSIS-ALBIKO.git
cd OSIS-ALBIKO
# Update config.php dengan credentials hosting
# Upload files ke public_html/
```

### 5. Folder Permissions

Pastikan folder ini writable (permission 755 atau 777):
```
uploads/
assets/
```

Via FTP: Set folder permissions ke 755
Via Terminal:
```bash
chmod 755 uploads/
chmod 755 assets/
```

### 6. SSL Certificate

Hosting sudah di domain HTTPS: https://osis-astamayana.space/

Pastikan SSL sudah enabled di cPanel

### 7. Testing

Setelah upload, test:
1. Buka https://osis-astamayana.space/
2. Coba login dengan akun admin
3. Test gallery upload
4. Test struktur organisasi management

### 8. Database Tables Reference

#### struktur_organisasi
```sql
CREATE TABLE struktur_organisasi (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tipe VARCHAR(50), -- 'pengurus' atau 'divisi'
  kategori VARCHAR(100),
  nama VARCHAR(255),
  posisi VARCHAR(100), -- 'Ketua' atau 'Anggota'
  urutan INT,
  dibuat_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  diupdate_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### galeri
```sql
CREATE TABLE galeri (
  id INT PRIMARY KEY AUTO_INCREMENT,
  judul VARCHAR(255),
  deskripsi TEXT,
  foto LONGTEXT, -- base64 encoded
  tipe_file VARCHAR(50),
  ukuran_file INT,
  dibuat_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### users
```sql
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  role VARCHAR(50), -- 'admin'
  token VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 9. API Endpoints

#### Auth
- `POST /auth-login.php` - Login
- `POST /auth-logout.php` - Logout
- `GET /auth-check.php` - Check token

#### Gallery
- `GET /get-galeri.php` - Get all gallery
- `POST /upload-galeri.php` - Upload photo
- `POST /delete-galeri.php` - Delete photo

#### Struktur Organisasi
- `GET /api-struktur.php?action=get` - Get struktur
- `POST /api-struktur.php?action=add` - Add anggota
- `POST /api-struktur.php?action=update` - Update anggota
- `POST /api-struktur.php?action=delete` - Delete anggota

### 10. Important Files

| File | Purpose |
|------|---------|
| `config.php` | Main configuration (auto-detects environment) |
| `index.php` | Homepage |
| `dashboard.php` | Admin dashboard |
| `edit-konten.php` | Content & struktur management |
| `connect-auth.php` | Database connection (legacy) |
| `connect.php` | Database connection (legacy) |
| `api-struktur.php` | Struktur CRUD API |

### 11. Troubleshooting

**Error: "Connection failed"**
- Check DB_HOST, DB_USER, DB_PASS di config.php
- Verify database exists di hosting

**Error: "Permission denied" for uploads/**
- Set folder permissions ke 755 atau 777
- Check if uploads/ folder exists

**Logo/Images tidak muncul**
- Check SITE_URL di config.php sesuai dengan domain hosting
- Verify assets/ folder ada dan accessible

**Gallery foto error**
- Pastikan file size < 5MB
- Check uploads/ folder writable permissions

### 12. Monitoring & Maintenance

Check logs regularly:
- Cek error logs di hosting control panel
- Monitor database growth
- Backup database regularly

---

**Setup selesai! 🎉**

Jika ada pertanyaan, check repo issues atau hubungi admin.
