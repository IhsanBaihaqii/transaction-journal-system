-- Impor file ini melalui phpMyAdmin pada XAMPP.
CREATE DATABASE IF NOT EXISTS sia_pemula CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sia_pemula;

-- Satu baris mewakili satu bukti penjualan.
CREATE TABLE penjualan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    pelanggan VARCHAR(100) NOT NULL,
    keterangan VARCHAR(200) NOT NULL,
    jumlah DECIMAL(12,2) NOT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Setiap penjualan menghasilkan dua baris jurnal.
CREATE TABLE jurnal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    penjualan_id INT NOT NULL,
    akun VARCHAR(30) NOT NULL,
    debit DECIMAL(12,2) NOT NULL DEFAULT 0,
    kredit DECIMAL(12,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (penjualan_id) REFERENCES penjualan(id),
    UNIQUE KEY unik_penjualan_akun (penjualan_id, akun)
);
