<?php
require '../koneksi.php';

// Form hanya boleh dikirim dengan metode POST.
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php');
    exit;
}

$tanggal = $_POST['tanggal'] ?? '';
$pelanggan = trim($_POST['pelanggan'] ?? '');
$keterangan = trim($_POST['keterangan'] ?? '');
$jumlah = $_POST['jumlah'] ?? '';

// Materi awal menggunakan nominal rupiah bulat agar mudah dihitung.
$tanggalBenar = DateTime::createFromFormat('!Y-m-d', $tanggal);
$valid = $tanggalBenar && $tanggalBenar->format('Y-m-d') == $tanggal
    && $pelanggan != '' && $keterangan != ''
    && strlen($pelanggan) <= 100 && strlen($keterangan) <= 200
    && ctype_digit((string)$jumlah) && (float)$jumlah > 0
    && (float)$jumlah <= 9999999999;

if (!$valid) {
    header('Location: ../index.php?pesan=gagal');
    exit;
}

// Satu transaksi database memastikan penjualan dan jurnal tersimpan bersama.
mysqli_begin_transaction($koneksi);
try {
    // Tanda ? adalah tempat data formulir. Ini mencegah SQL injection.
    $sql = 'INSERT INTO penjualan (tanggal, pelanggan, keterangan, jumlah) VALUES (?, ?, ?, ?)';
    $perintah = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($perintah, 'ssss', $tanggal, $pelanggan, $keterangan, $jumlah);
    mysqli_stmt_execute($perintah);

    // Ambil ID penjualan untuk menghubungkan dua baris jurnal.
    $idPenjualan = mysqli_insert_id($koneksi);

    // Jurnal baris pertama: Kas bertambah, dicatat di debit.
    $akun = 'Kas';
    $debit = $jumlah;
    $kredit = '0';
    $sqlJurnal = 'INSERT INTO jurnal (penjualan_id, akun, debit, kredit) VALUES (?, ?, ?, ?)';
    $jurnal = mysqli_prepare($koneksi, $sqlJurnal);
    mysqli_stmt_bind_param($jurnal, 'isss', $idPenjualan, $akun, $debit, $kredit);
    mysqli_stmt_execute($jurnal);

    // Jurnal baris kedua: pendapatan Penjualan dicatat di kredit.
    $akun = 'Penjualan';
    $debit = '0';
    $kredit = $jumlah;
    mysqli_stmt_execute($jurnal);

    mysqli_commit($koneksi);
    header('Location: ../index.php?pesan=berhasil');
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    header('Location: ../index.php?pesan=gagal');
}
exit;
