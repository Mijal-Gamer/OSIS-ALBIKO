-- OSIS Astamayana Database Setup Script
-- Execute this in your MySQL admin panel

-- Create main database
CREATE DATABASE IF NOT EXISTS osis;
USE osis;

-- Table untuk konten halaman
CREATE TABLE IF NOT EXISTS halaman (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul_tentang VARCHAR(255) DEFAULT 'Tentang OSIS Astamayana',
    isi_tentang LONGTEXT DEFAULT 'OSIS Astamayana adalah organisasi siswa intra sekolah yang berfokus pada pengembangan kreativitas, solidaritas, dan kepemimpinan.',
    judul_kegiatan VARCHAR(255) DEFAULT 'Kegiatan OSIS',
    isi_kegiatan LONGTEXT DEFAULT 'Berbagai kegiatan menarik menunggu untuk Anda ikuti.',
    instagram VARCHAR(255) DEFAULT 'https://www.instagram.com/osisalbidskh',
    tiktok VARCHAR(255) DEFAULT 'https://www.tiktok.com/@osis.albiko',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default data
INSERT INTO halaman (id, judul_tentang, isi_tentang, judul_kegiatan, isi_kegiatan, instagram, tiktok) 
VALUES (1, 'Tentang OSIS Astamayana', 'OSIS Astamayana adalah organisasi siswa intra sekolah yang berfokus pada pengembangan kreativitas, solidaritas, dan kepemimpinan.', 'Kegiatan OSIS', 'Berbagai kegiatan menarik menunggu untuk Anda ikuti.', 'https://www.instagram.com/osisalbidskh', 'https://www.tiktok.com/@osis.albiko');

-- Create auth database
CREATE DATABASE IF NOT EXISTS osis_auth;
USE osis_auth;

-- Table untuk users/admin
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    role ENUM('admin', 'user') DEFAULT 'user',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (username: admin, password: admin123)
INSERT INTO users (username, password, email, full_name, role) 
VALUES ('admin', '$2y$10$8/5F5y5X9z9p5q5r5s5t5u5v5w5x5y5z5a5b5c5d5e5f5g5h5i5j5k5', 'admin@osis.local', 'Administrator', 'admin');

-- Table untuk activity log
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(255),
    details TEXT,
    ip_address VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table untuk settings
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    key_name VARCHAR(100) UNIQUE NOT NULL,
    key_value LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO settings (key_name, key_value) VALUES 
('site_name', 'OSIS Astamayana'),
('site_description', 'Organisasi Siswa Intra Sekolah - SMP AL ABIDIN Sukoharjo'),
('site_url', 'http://localhost/OSIS-ALBIKO/'),
('maintenance_mode', 'false'),
('items_per_page', '10');

-- Create indexes for better performance
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_activity_logs_user_id ON activity_logs(user_id);
CREATE INDEX idx_activity_logs_created_at ON activity_logs(created_at);
CREATE INDEX idx_settings_key_name ON settings(key_name);