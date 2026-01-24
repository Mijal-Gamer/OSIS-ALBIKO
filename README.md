# OSIS Astamayana Website

Website resmi OSIS (Organisasi Siswa Intra Sekolah) Astamayana - SMP AL ABIDIN Sukoharjo

## Fitur Utama

- **Homepage** - Halaman depan dengan informasi tentang OSIS
- **Admin Panel** - Dashboard untuk mengelola konten
- **Edit Konten** - Interface untuk update informasi
- **Feedback System** - Sistem feedback dari pengunjung berbasis Firebase
- **Responsive Design** - Adaptif di semua ukuran layar

## Teknologi

- **Backend**: PHP dengan prepared statements
- **Database**: MySQL (osis, osis_auth)
- **Frontend**: Vanilla JavaScript + Quill.js untuk editor
- **Real-time**: Firebase Realtime Database
- **Icons**: Remixicon
- **Styling**: Custom CSS dengan animasi

## Struktur File

```
OSIS-ALBIKO/
├── index.php              # Homepage
├── login.php              # Login page
├── dashboard.php          # Admin dashboard
├── edit-konten.php        # Content editor
├── feedback.php           # Feedback viewer
├── update-konten.php      # API untuk update konten
├── handle-login.php       # Login handler
├── auth-check.php         # Session checker
├── logout.php             # Logout handler
├── connect.php            # Main DB connection
├── connect-auth.php       # Auth DB connection
├── test.php               # Test connection
├── assets/
│   ├── firebase-config.js
│   ├── css/
│   │   ├── style.css
│   │   └── admin-style.css
│   ├── js/
│   │   └── dashboard.js
│   └── images/
└── README.md
```

## Setup

1. Copy semua file ke folder `htdocs/OSIS-ALBIKO`
2. Create database:
   - `osis` - untuk konten website
   - `osis_auth` - untuk authentication users
3. Create table `users` di `osis_auth`:
   ```sql
   CREATE TABLE users (
       id INT PRIMARY KEY AUTO_INCREMENT,
       username VARCHAR(50) UNIQUE NOT NULL,
       password VARCHAR(255) NOT NULL
   );
   ```
4. Create table `halaman` di `osis`:
   ```sql
   CREATE TABLE halaman (
       id INT PRIMARY KEY AUTO_INCREMENT,
       judul_tentang VARCHAR(255),
       isi_tentang LONGTEXT,
       judul_kegiatan VARCHAR(255),
       isi_kegiatan LONGTEXT,
       instagram VARCHAR(255),
       tiktok VARCHAR(255)
   );
   ```
5. Insert sample user:
   ```sql
   INSERT INTO users (username, password) VALUES ('admin', 'admin123');
   ```

## Default Login

- Username: `admin`
- Password: `admin123`

## Fitur

### Halaman Publik (index.php)
- Informasi tentang OSIS
- Struktur organisasi
- Galeri kegiatan
- Contact & social media
- Feedback form dengan Firebase

### Admin Area
- Dashboard dengan statistics
- Edit konten halaman utama
- Lihat dan hapus feedback
- Logout

## Firebase Config

Gunakan Firebase Realtime Database untuk menyimpan feedback pengunjung. Update `firebaseConfig` di file yang menggunakan Firebase.

## Author

Tim HUMAS OSIS Astamayana

---

**Last Updated**: 22 Januari 2026