-- ========================================
-- DATABASE PEMESANAN BAJU BOLA KELUARGA
-- ========================================

-- Membuat database (opsional, sesuaikan dengan kebutuhan)
CREATE DATABASE IF NOT EXISTS `pemesanan_baju_bola` 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `pemesanan_baju_bola`;

-- ========================================
-- TABEL 1: PESANAN UTAMA (Data Pemesan)
-- ========================================
CREATE TABLE `pesanan_utama` (
  `id_pesanan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pemesan` varchar(100) NOT NULL,
  `nomor_whatsapp` varchar(20) NOT NULL,
  `nomor_punggung` int(3) NOT NULL,
  `total_pesanan` int(3) NOT NULL COMMENT 'Jumlah baju yang dipesan',
  `total_harga` decimal(10,2) NOT NULL,
  `status_pesanan` enum('pending','confirmed','processing','completed','cancelled') DEFAULT 'pending',
  `tanggal_pesan` timestamp DEFAULT CURRENT_TIMESTAMP,
  `tanggal_update` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pesanan`),
  INDEX `idx_nomor_whatsapp` (`nomor_whatsapp`),
  INDEX `idx_tanggal_pesan` (`tanggal_pesan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABEL 2: DETAIL ANGGOTA KELUARGA
-- ========================================
CREATE TABLE `detail_anggota` (
  `id_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_pesanan` int(11) NOT NULL,
  `nama_anggota` varchar(100) NOT NULL,
  `umur` int(3) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `ukuran_baju` varchar(20) NOT NULL,
  `harga_baju` decimal(8,2) NOT NULL,
  `nomor_punggung` int(3) NOT NULL COMMENT 'Sama dengan nomor punggung pemesan utama',
  `urutan_anggota` int(2) NOT NULL COMMENT 'Urutan anggota dalam keluarga (1=pemesan utama)',
  PRIMARY KEY (`id_detail`),
  FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan_utama`(`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX `idx_id_pesanan` (`id_pesanan`),
  INDEX `idx_ukuran_baju` (`ukuran_baju`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABEL 3: MASTER UKURAN & HARGA (Referensi)
-- ========================================
CREATE TABLE `master_ukuran` (
  `id_ukuran` int(11) NOT NULL AUTO_INCREMENT,
  `ukuran_baju` varchar(20) NOT NULL UNIQUE,
  `kategori` enum('Anak','Dewasa') NOT NULL,
  `harga` decimal(8,2) NOT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `tanggal_buat` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ukuran`),
  INDEX `idx_kategori` (`kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- INSERT DATA MASTER UKURAN & HARGA
-- ========================================
INSERT INTO `master_ukuran` (`ukuran_baju`, `kategori`, `harga`) VALUES
('S Anak', 'Anak', 80000.00),
('M Anak', 'Anak', 80000.00),
('L Anak', 'Anak', 80000.00),
('Dewasa S', 'Dewasa', 100000.00),
('Dewasa M', 'Dewasa', 100000.00),
('Dewasa L', 'Dewasa', 100000.00),
('Dewasa XL', 'Dewasa', 110000.00),
('Dewasa XXL', 'Dewasa', 110000.00),
('Dewasa XXXL', 'Dewasa', 120000.00);

-- ========================================
-- CONTOH QUERY INSERT DATA PESANAN
-- ========================================

-- 1. Insert ke tabel pesanan_utama
INSERT INTO `pesanan_utama` 
(`nama_pemesan`, `nomor_whatsapp`, `nomor_punggung`, `total_pesanan`, `total_harga`) 
VALUES 
('Budi Santoso', '08123456789', 10, 3, 290000.00);

-- Ambil ID pesanan yang baru saja dibuat
SET @id_pesanan_baru = LAST_INSERT_ID();

-- 2. Insert ke tabel detail_anggota (untuk setiap anggota keluarga)
INSERT INTO `detail_anggota` 
(`id_pesanan`, `nama_anggota`, `umur`, `jenis_kelamin`, `ukuran_baju`, `harga_baju`, `nomor_punggung`, `urutan_anggota`) 
VALUES 
(@id_pesanan_baru, 'Budi Santoso', 35, 'Laki-laki', 'Dewasa L', 100000.00, 10, 1),
(@id_pesanan_baru, 'Siti Aminah', 32, 'Perempuan', 'Dewasa M', 100000.00, 10, 2),
(@id_pesanan_baru, 'Ahmad Budi', 8, 'Laki-laki', 'L Anak', 80000.00, 10, 3);

-- ========================================
-- QUERY UNTUK MENGAMBIL DATA PESANAN
-- ========================================

-- Melihat semua pesanan dengan detail
SELECT 
    p.id_pesanan,
    p.nama_pemesan,
    p.nomor_whatsapp,
    p.nomor_punggung,
    p.total_pesanan,
    p.total_harga,
    p.status_pesanan,
    p.tanggal_pesan,
    d.nama_anggota,
    d.umur,
    d.jenis_kelamin,
    d.ukuran_baju,
    d.harga_baju,
    d.urutan_anggota
FROM pesanan_utama p
LEFT JOIN detail_anggota d ON p.id_pesanan = d.id_pesanan
ORDER BY p.id_pesanan DESC, d.urutan_anggota ASC;

-- Melihat ringkasan pesanan tertentu
SELECT 
    p.*,
    COUNT(d.id_detail) as jumlah_anggota_tercatat
FROM pesanan_utama p
LEFT JOIN detail_anggota d ON p.id_pesanan = d.id_pesanan
WHERE p.id_pesanan = 1  -- Ganti dengan ID pesanan yang diinginkan
GROUP BY p.id_pesanan;

-- Melihat detail anggota untuk pesanan tertentu
SELECT * FROM detail_anggota 
WHERE id_pesanan = 1  -- Ganti dengan ID pesanan yang diinginkan
ORDER BY urutan_anggota ASC;

-- ========================================
-- QUERY UNTUK LAPORAN & STATISTIK
-- ========================================

-- Laporan penjualan per ukuran
SELECT 
    ukuran_baju,
    COUNT(*) as jumlah_terjual,
    SUM(harga_baju) as total_pendapatan
FROM detail_anggota
GROUP BY ukuran_baju
ORDER BY jumlah_terjual DESC;

-- Laporan pesanan per bulan
SELECT 
    DATE_FORMAT(tanggal_pesan, '%Y-%m') as bulan,
    COUNT(*) as jumlah_pesanan,
    SUM(total_pesanan) as total_baju,
    SUM(total_harga) as total_pendapatan
FROM pesanan_utama
GROUP BY DATE_FORMAT(tanggal_pesan, '%Y-%m')
ORDER BY bulan DESC;

-- ========================================
-- QUERY UNTUK UPDATE STATUS PESANAN
-- ========================================

-- Update status pesanan
UPDATE pesanan_utama 
SET status_pesanan = 'confirmed' 
WHERE id_pesanan = 1;  -- Ganti dengan ID pesanan yang diinginkan

-- ========================================
-- INDEX TAMBAHAN UNTUK PERFORMA
-- ========================================

-- Index untuk pencarian berdasarkan nama pemesan
CREATE INDEX idx_nama_pemesan ON pesanan_utama(nama_pemesan);

-- Index untuk pencarian berdasarkan status
CREATE INDEX idx_status_pesanan ON pesanan_utama(status_pesanan);

-- Index untuk pencarian berdasarkan nama anggota
CREATE INDEX idx_nama_anggota ON detail_anggota(nama_anggota);