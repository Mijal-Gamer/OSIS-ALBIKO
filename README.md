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
