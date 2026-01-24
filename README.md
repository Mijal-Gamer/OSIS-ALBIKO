# OSIS Astamayana Website

Website resmi OSIS (Organisasi Siswa Intra Sekolah) Astamayana - SMP AL ABIDIN Sukoharjo

🌐 **Live Website:** https://osis-astamayana.space/

📦 **Repository:** https://github.com/Mijal-Gamer/OSIS-ALBIKO

## ✨ Fitur Utama

- **Homepage** - Halaman depan dengan informasi tentang OSIS
- **Admin Panel** - Dashboard untuk mengelola konten
- **Edit Konten** - Interface untuk update informasi & media
- **Struktur Organisasi** - Kelola struktur OSIS (Pembina, Pengurus, Divisi) dengan database
- **Gallery Dinamis** - Upload dan manage gallery foto dengan mudah
- **Feedback System** - Sistem feedback dari pengunjung berbasis Firebase
- **Authentication** - Login aman dengan token-based authentication
- **Responsive Design** - Website responsive untuk desktop, tablet, mobile

## 🚀 Tech Stack

### Frontend
- HTML5, CSS3, JavaScript (Vanilla)
- Responsive Grid Layout
- Modern UI/UX dengan animasi smooth
- RemixIcon untuk icons

### Backend
- PHP 7.4+
- MySQL Database (osis_auth, osis)
- RESTful API endpoints
- Token-based Authentication

### Integrations
- Firebase Realtime Database (feedback system)
- Google Authentication (upcoming)

## 📋 Struktur Database

### Database 1: `osis_auth`
```
- users (username, password, role, token)
- struktur_organisasi (tipe, kategori, nama, posisi, urutan)
- galeri (judul, deskripsi, foto, tipe_file)
```

### Database 2: `osis`
```
- Main content database (if needed for other data)
```

## 🔧 Local Development Setup

### Requirements
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx
- Git

### Installation

1. **Clone Repository**
   ```bash
   git clone https://github.com/Mijal-Gamer/OSIS-ALBIKO.git
   cd OSIS-ALBIKO
   ```

2. **Setup Database**
   ```sql
   CREATE DATABASE osis_auth;
   CREATE DATABASE osis;
   ```
   - Import database structure (see DEPLOYMENT.md)

3. **Configure Database Connection**
   ```php
   // config.php (auto-detects localhost environment)
   // No changes needed for local development
   // config.php will use root/[empty] for localhost
   ```

4. **Start Development Server**
   ```bash
   # Using XAMPP
   # Just place folder in htdocs/ and start Apache + MySQL
   
   # Or using PHP built-in server
   php -S localhost:8000
   ```

5. **Access Website**
   ```
   http://localhost/OSIS-ALBIKO
   ```

## 📚 API Endpoints

### Authentication
- `POST /auth-login.php` - Login dengan username/password
- `POST /auth-logout.php` - Logout
- `GET /auth-check.php` - Verify token

### Gallery Management
- `GET /get-galeri.php` - Get semua gallery items
- `POST /upload-galeri.php` - Upload foto baru
- `POST /delete-galeri.php` - Hapus foto

### Struktur Organisasi
- `GET /api-struktur.php?action=get` - Get struktur items
- `POST /api-struktur.php?action=add` - Add anggota baru
- `POST /api-struktur.php?action=update` - Update anggota
- `POST /api-struktur.php?action=delete` - Delete anggota

## 📄 File Structure

```
OSIS-ALBIKO/
├── index.php                 # Homepage
├── dashboard.php             # Admin dashboard
├── edit-konten.php          # Content & struktur management
├── auth-login.php           # Login handler
├── auth-logout.php          # Logout handler
├── api-struktur.php         # Struktur CRUD API
├── get-galeri.php           # Get gallery endpoint
├── upload-galeri.php        # Upload gallery endpoint
├── delete-galeri.php        # Delete gallery endpoint
├── config.php               # Configuration (auto-detects env)
├── connect.php              # DB connection (legacy)
├── connect-auth.php         # DB connection (legacy)
├── backup-database.php      # Database backup script
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   └── admin-style.css
│   ├── js/
│   │   └── dashboard.js
│   └── images/
├── uploads/                 # Gallery uploads directory
├── firebase-config.js       # Firebase configuration
├── DEPLOYMENT.md            # Deployment guide
└── SETUP_HOSTING.md        # Production hosting setup guide
```

## 👤 Default Admin Credentials

```
Username: admin
Password: admin123
```

⚠️ **IMPORTANT:** Change password immediately after first login!

## 🔐 Security Features

- Token-based authentication
- Prepared statements (SQL injection prevention)
- Password hashing (SHA2)
- HTTPS support
- CORS protection
- File upload validation
- XSS protection

## 📝 Features Documentation

### Struktur Organisasi Management
- View semua struktur (Pembina, Pengurus Inti, Divisi)
- Add/Edit/Delete anggota
- Set posisi (Ketua, Anggota)
- Automatic sync ke homepage

### Gallery Management
- Upload foto (max 5MB)
- Auto base64 encoding untuk database storage
- Display di homepage
- Delete with confirmation

### Admin Dashboard
- View semua aktivitas
- Quick access ke semua features
- Responsive design untuk mobile

## 🚀 Deployment

For production deployment to https://osis-astamayana.space/:

1. Read [SETUP_HOSTING.md](./SETUP_HOSTING.md) untuk step-by-step guide
2. Prepare hosting database credentials
3. Upload files via FTP
4. Update config.php dengan hosting database
5. Create databases dan tables
6. Test semua functionality

## 🐛 Troubleshooting

### Common Issues

**Database Connection Error**
- Check DB_HOST, DB_USER, DB_PASS di config.php
- Verify database exists
- Ensure user has proper privileges

**Upload Gallery Error**
- Check uploads/ folder permissions (755)
- Verify file size < 5MB
- Check disk space

**Struktur Organisasi Not Showing**
- Verify database tables created
- Check api-struktur.php response
- Clear browser cache

## 📞 Support

For issues or questions:
1. Check GitHub Issues
2. Review documentation files
3. Contact developer

## 📄 License

Project ini adalah milik OSIS Astamayana SMP AL ABIDIN Sukoharjo

## 👥 Contributors

- **Developed by:** GitHub User [Mijal-Gamer](https://github.com/Mijal-Gamer)
- **School:** SMP AL ABIDIN Sukoharjo
- **Organization:** OSIS Astamayana
- **Responsive Design** - Adaptif di semua ukuran layar

## Teknologi

- **Backend**: PHP dengan prepared statements
- **Database**: MySQL (osis, osis_auth)
- **Frontend**: Vanilla JavaScript + Quill.js untuk editor
- **Real-time**: Firebase Realtime Database
- **Icons**: Remixicon
- **Styling**: Custom CSS dengan animasi


## Default Login

- Username: `*****`
- Password: `*******`

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

**Last Updated**: 24 Januari 2026
