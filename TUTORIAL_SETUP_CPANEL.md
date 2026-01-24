# 📖 Tutorial Upload OSIS Astamayana ke Hosting cPanel

Panduan lengkap untuk upload project OSIS Astamayana ke hosting dengan cPanel (seperti di artikel Laravel).

## 📋 Daftar Isi
- [Step 1: Upload Files ke Hosting](#step-1-upload-files)
- [Step 2: Extract & Organize Files](#step-2-extract--organize)
- [Step 3: Create Databases](#step-3-create-databases)
- [Step 4: Create Database User](#step-4-create-database-user)
- [Step 5: Configure Database](#step-5-configure-database)
- [Step 6: Import Database](#step-6-import-database)
- [Step 7: Test Website](#step-7-test-website)

---

## Step 1: Upload Files

### 1.1 Login ke cPanel

1. Buka email hosting Anda
2. Cari username dan password cPanel
3. Login ke: `https://domain.com:2083` atau URL yang diberikan hosting
4. Masukkan username dan password cPanel

### 1.2 Buka File Manager

Di dashboard cPanel:
1. Cari menu **"File Manager"**
2. Klik untuk membuka

![File Manager Location]
```
Dashboard cPanel
├── Files
│   └── File Manager ← KLIK DI SINI
```

### 1.3 Upload Project Files

1. Di File Manager, klik tombol **"Upload"** di bagian atas
2. Pilih file `OSIS-ALBIKO.zip` dari komputer Anda
3. Tunggu sampai upload selesai (lihat progress bar)

Contoh:
```
public_html/
├── OSIS-ALBIKO.zip ← File yang di-upload
├── index.html (default)
└── ...
```

---

## Step 2: Extract & Organize

### 2.1 Extract ZIP File

1. Setelah upload selesai, cari file `OSIS-ALBIKO.zip`
2. **Klik kanan** pada file tersebut
3. Pilih **"Extract"**
4. Pilih folder tujuan: `public_html` (biarkan default)
5. Klik **"Extract"**

### 2.2 Hasil Setelah Extract

Setelah extract, struktur folder akan seperti ini:

```
public_html/
├── OSIS-ALBIKO/
│   ├── index.php
│   ├── dashboard.php
│   ├── edit-konten.php
│   ├── config.php
│   ├── api-struktur.php
│   ├── assets/
│   ├── uploads/
│   └── ...
├── OSIS-ALBIKO.zip (bisa dihapus)
└── ...
```

### 2.3 Atur Struktur (PENTING!)

⚠️ **Karena project harus bisa diakses dari domain root**, kita perlu move files:

1. Masuk ke folder `OSIS-ALBIKO`
2. **Select ALL files** (Ctrl+A atau klik "Select All")
3. Klik tombol **"Move"**
4. Input path: `../` (parent directory)
5. Klik **"Move"**

**Hasilnya:**
```
public_html/
├── index.php ← Sekarang langsung di sini
├── dashboard.php
├── edit-konten.php
├── config.php
├── api-struktur.php
├── assets/
├── uploads/
├── OSIS-ALBIKO/ (folder kosong, bisa dihapus)
└── ...
```

✅ Sekarang project bisa diakses dari: `https://domain.com/`

---

## Step 3: Create Databases

### 3.1 Buka MySQL Database Menu

Di cPanel Dashboard:
1. Cari **"MySQL Databases"** atau **"Manage My Databases"**
2. Klik untuk membuka

### 3.2 Database Sudah Ada di Hosting ✅

⚠️ **Hosting sudah membuat database!** Anda bisa skip step ini.

**Database yang sudah ada:**
```
✅ wwoiodev_osis_auth
✅ wwoiodev_osis
```

Kedua database sudah siap digunakan untuk project OSIS Astamayana!

---

## Step 4: Create Database User

### 4.1 Database Credentials (Sudah Ada) ✅

⚠️ **Hosting sudah membuat database user!**

**Database Credentials dari Hosting:**
```
Database Username: wwoiodev_Admin
Database Password: qwertyuiop89001
Database Host: localhost
```

### 4.2 Catat Credentials

Informasi yang sudah diperoleh:
```
Username: wwoiodev_Admin
Password: qwertyuiop89001
Database 1: wwoiodev_osis_auth
Database 2: wwoiodev_osis
Host: localhost
```

⚠️ Simpan baik-baik, dibutuhkan di Step 5!

---

## Step 5: Configure Database

### 5.1 Edit config.php

1. Buka File Manager
2. Navigate ke `public_html/` (folder project)
3. Cari file **`config.php`**
4. Klik **"Edit"** (atau Edit with Editor)

### 5.2 Update Database Credentials

Cari baris ini (sekitar line 11-14):

```php
// PRODUCTION (osis-astamayana.space)
define('DB_HOST', 'localhost');
define('DB_USER', 'osis_user');
define('DB_PASS', 'your_secure_password');
```

**GANTI dengan:**

```php
// PRODUCTION (osis-astamayana.space)
define('DB_HOST', 'localhost');
define('DB_USER', 'wwoiodev_Admin');       // Username dari hosting
define('DB_PASS', 'qwertyuiop89001');      // Password dari hosting
```

Selesai! Database sudah di-configure.

### 5.3 Save File

1. Klik **"Save Changes"** atau **"Save"**
2. File sudah ter-update

✅ Config sudah siap!

---

## Step 6: Import Database

### 6.1 Buka phpMyAdmin

Di cPanel Dashboard:
1. Cari **"phpMyAdmin"**
2. Klik untuk membuka (akan membuka tab baru)

### 6.2 Select Database wwoiodev_osis_auth

1. Di sidebar kiri phpMyAdmin
2. Klik database **`wwoiodev_osis_auth`**
3. Database terbuka (akan kosong/belum ada tables)

### 6.3 Import Database Structure

1. Klik tab **"Import"** di bagian atas
2. Klik **"Choose File"**
3. Upload file SQL (dari repo atau buat baru)

**File SQL dari repo:**
- Atau buat manual dengan query di bawah

### 6.4 Create Tables Manually

Jika tidak ada file SQL, buat tables dengan query berikut:

**Klik tab "SQL" dan copy-paste:**

```sql
-- Create users table
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'admin',
  `token` varchar(255) COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create struktur_organisasi table
CREATE TABLE `struktur_organisasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipe` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `posisi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `urutan` int(11) DEFAULT 0,
  `dibuat_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diupdate_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create galeri table
CREATE TABLE `galeri` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `foto` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `tipe_file` varchar(50) COLLATE utf8mb4_general_ci,
  `ukuran_file` int(11),
  `dibuat_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default admin user (CHANGE PASSWORD AFTER FIRST LOGIN!)
INSERT INTO `users` (`username`, `password`, `role`) 
VALUES ('admin', SHA2('admin123', 256), 'admin');

-- Insert sample struktur data (optional)
INSERT INTO `struktur_organisasi` (`tipe`, `kategori`, `nama`, `posisi`, `urutan`) VALUES
('pengurus', 'Pembina', 'Valensia Ihsa Mahendra, S.Pd', 'Pembina', 1),
('pengurus', 'Pembina', 'Afifiah Abidah, S.Pd', 'Wakil Pembina', 2),
('pengurus', 'Pengurus Inti', 'Qori\' Ahsan', 'Ketua', 1),
('pengurus', 'Pengurus Inti', 'Arin Kusuma Dewi', 'Wakil Ketua', 2),
('divisi', 'APK', 'Irsyad Pradipta Yuswardhana', 'Ketua', 1),
('divisi', 'Humas', 'Mifzal Kanzie Raharjo', 'Ketua', 1),
('divisi', 'Komdis', 'Azka Muhammad Alfarizyq', 'Ketua', 1),
('divisi', 'Korseni', 'Akia Destin Kenzie Nararya', 'Ketua', 1),
('divisi', 'KPA', 'Kenzie Mirza Manggala', 'Ketua', 1),
('divisi', 'Rohis', 'Daffa Syadad Nur Faisal', 'Ketua', 1);
```

1. Copy query di atas
2. Paste ke text area **"SQL"**
3. Klik **"Go"** atau **"Execute"**
4. Tunggu sampai selesai

### 6.5 Verify Tables

Setelah query berhasil:
1. Refresh phpMyAdmin
2. Di sidebar, buka database `wwoiodev_osis_auth`
3. Verify tables ada:
   ```
   ✅ users
   ✅ struktur_organisasi
   ✅ galeri
   ```

### 6.6 Repeat untuk Database wwoiodev_osis

1. Klik database **`wwoiodev_osis`** di sidebar
2. Jalankan query yang sama (atau biarkan kosong jika tidak diperlukan)

---

## Step 7: Test Website

### 7.1 Buka Website

Buka browser dan akses:
```
https://domain.com/
```

atau

```
https://osis-astamayana.space/
```

### 7.2 Verify Homepage

Pastikan muncul:
- ✅ Logo OSIS
- ✅ Struktur Organisasi section
- ✅ Gallery section
- ✅ Semua styling dan layout

### 7.3 Test Login

1. Buka: `https://domain.com/dashboard.php`
2. Login dengan:
   - **Username:** `admin`
   - **Password:** `admin123`
3. Jika berhasil login, database connect OK!

### 7.4 Test Features

- Coba upload gallery foto
- Coba edit struktur organisasi
- Verify semua berfungsi

⚠️ **PENTING:** Setelah login berhasil, **CHANGE PASSWORD ADMIN!**

---

## 🔐 Change Admin Password (PENTING!)

### Opsi 1: Via phpMyAdmin

1. Buka phpMyAdmin
2. Database `cpuser_osis_auth`
3. Tabel `users`
4. Klik row dengan username `admin`
5. Edit field `password`
6. Ganti password dengan hash SHA2:
   ```
   Gunakan function: SHA2('password_baru', 256)
   ```
7. Save

### Opsi 2: Via dashboard (jika ada menu change password)

1. Login ke dashboard
2. Cari menu "Change Password" atau "Settings"
3. Update password

---

## ✅ Checklist Setup

```
Database Setup:
☐ Database wwoiodev_osis_auth sudah ada ✅
☐ Database wwoiodev_osis sudah ada ✅
☐ Database username: wwoiodev_Admin ✅
☐ Database password: qwertyuiop89001 ✅
☐ Update config.php dengan database credentials ← LANGKAH PENTING!

Database Tables:
☐ Create table users
☐ Create table struktur_organisasi
☐ Create table galeri
☐ Insert default admin user
☐ (Optional) Insert sample struktur data

Website Testing:
☐ Homepage load correctly
☐ Login berhasil dengan admin/admin123
☐ Gallery upload test
☐ Struktur organisasi edit test
☐ Change admin password

Final:
☐ Test semua fitur utama
☐ Check error logs jika ada masalah
☐ Setup SSL (sudah ada di domain)
☐ Database backup scheduled
```

---

## 🆘 Troubleshooting

### ❌ "Connection failed" Error

**Problem:** Tidak bisa connect ke database

**Solution:**
1. Verify database credentials di config.php
2. Check database user assigned ke database
3. Verify user has ALL PRIVILEGES
4. Check database host = `localhost`

### ❌ "Table not found" Error

**Problem:** Tables tidak ada di database

**Solution:**
1. Verify semua CREATE TABLE query sudah execute
2. Check di phpMyAdmin apakah tables sudah ada
3. Re-execute SQL queries jika perlu
4. Verify charset = utf8mb4

### ❌ "Permission denied" pada uploads/

**Problem:** Tidak bisa upload gallery

**Solution:**
1. File Manager → uploads folder
2. Klik kanan → Properties
3. Set Permission: `755`
4. Apply changes

### ❌ Asset files (CSS, JS) tidak load

**Problem:** Style & JavaScript tidak muncul

**Solution:**
1. Check browser console (F12) untuk 404 errors
2. Verify assets/ folder uploaded completely
3. Check SITE_URL di config.php sesuai domain
4. Refresh browser dan clear cache (Ctrl+Shift+Del)

---

## 📞 Need Help?

Jika ada error atau pertanyaan:
1. Check error logs di cPanel
2. Check phpMyAdmin database structure
3. Check browser console (F12)
4. Contact hosting support

**Hosting Admin Email:**
```
Semua informasi database sudah lengkap:
✅ Database 1: wwoiodev_osis
✅ Database 2: wwoiodev_osis_auth
✅ Database Username: wwoiodev_Admin
✅ Database Password: qwertyuiop89001
```

---

## ✨ Setup Complete! 🎉

Jika semua step selesai tanpa error, **OSIS Astamayana production website sudah live!**

- Website: https://osis-astamayana.space/
- Admin: https://osis-astamayana.space/dashboard.php
- Database: wwoiodev_osis_auth (production), wwoiodev_osis (main)

**Selamat!** Website OSIS Astamayana sudah berjalan di hosting! 🚀
