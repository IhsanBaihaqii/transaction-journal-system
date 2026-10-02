CREATE DATABASE IF NOT EXISTS sia_pemula
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE sia_pemula;

CREATE TABLE penjualan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    pelanggan VARCHAR(100) NOT NULL,
    keterangan VARCHAR(200) NOT NULL,
    jumlah DECIMAL(12,2) NOT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pembelian (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    pemasok VARCHAR(100) NOT NULL,
    keterangan VARCHAR(200) NOT NULL,
    jumlah DECIMAL(12,2) NOT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE jurnal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jenis_transaksi ENUM('penjualan', 'pembelian') NOT NULL,
    referensi_id INT NOT NULL,
    tanggal DATE NOT NULL,
    akun VARCHAR(50) NOT NULL,
    debit DECIMAL(12,2) NOT NULL DEFAULT 0,
    kredit DECIMAL(12,2) NOT NULL DEFAULT 0,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_referensi (jenis_transaksi, referensi_id),
    INDEX idx_tanggal (tanggal)
);