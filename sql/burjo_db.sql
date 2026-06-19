-- Database untuk Web Burjo - Manajemen Menu Makanan & Minuman
-- Jalankan di phpMyAdmin atau MySQL client

CREATE DATABASE IF NOT EXISTS burjo_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE burjo_db;

-- Tabel Users (untuk Admin dan contoh Tamu)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'tamu') NOT NULL DEFAULT 'tamu',
    nama_lengkap VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Menu (Makanan & Minuman)
CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(10,2) NOT NULL,
    jenis ENUM('makanan', 'minuman') NOT NULL,
    gambar VARCHAR(255) DEFAULT NULL, -- nama file atau URL, simpan di public/uploads/menu/
    stok INT DEFAULT 10,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Seed Data: Admin user (password: admin123)
INSERT INTO users (username, password, role, nama_lengkap) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Administrator Burjo'),
('tamu1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tamu', 'Tamu Demo');

-- Seed Data Menu Makanan
INSERT INTO menu (nama, deskripsi, harga, jenis, gambar, stok) VALUES 
('Nasi Goreng Spesial', 'Nasi goreng dengan telur, ayam, dan sayuran segar khas Burjo', 15000, 'makanan', 'nasi_goreng.jpg', 20),
('Mie Ayam Bakso', 'Mie kuning dengan ayam suwir dan bakso sapi spesial', 12000, 'makanan', 'mie_ayam.jpg', 15),
('Nasi Campur', 'Nasi putih dengan lauk pauk lengkap: ayam, telur, tempe, tahu', 18000, 'makanan', 'nasi_campur.jpg', 10),
('Lontong Opor', 'Lontong dengan opor ayam dan sambal terasi', 14000, 'makanan', 'lontong_opor.jpg', 8);

-- Seed Data Menu Minuman
INSERT INTO menu (nama, deskripsi, harga, jenis, gambar, stok) VALUES 
('Es Teh Manis', 'Teh hitam segar dengan es dan gula pasir', 5000, 'minuman', 'es_teh.jpg', 50),
('Jus Alpukat', 'Jus alpukat segar dengan susu dan gula', 12000, 'minuman', 'jus_alpukat.jpg', 12),
('Es Jeruk Peras', 'Jeruk segar peras dengan es batu', 8000, 'minuman', 'es_jeruk.jpg', 25),
('Kopi Hitam Burjo', 'Kopi hitam khas Jogja, strong dan nikmat', 7000, 'minuman', 'kopi_hitam.jpg', 30),
('Wedang Jahe', 'Minuman jahe hangat dengan gula merah', 6000, 'minuman', 'wedang_jahe.jpg', 18);

-- Catatan: Password hash di atas adalah untuk 'admin123' dan 'tamu123'
-- Untuk production, ganti password admin!