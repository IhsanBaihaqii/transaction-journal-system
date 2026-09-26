<?php
// Sesuaikan empat nilai berikut jika pengaturan MySQL berbeda.
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'sia_pemula';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$koneksi = mysqli_connect($host, $user, $password, $database);
if (!$koneksi) {
    die('Koneksi gagal. Periksa koneksi.php dan impor database.sql.');
}
mysqli_set_charset($koneksi, 'utf8mb4');

// Agar teks yang tampil dari database aman ditampilkan di HTML.
function aman($teks) {
    return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8');
}

function rupiah($angka) {
    return 'Rp' . number_format((float)$angka, 0, ',', '.');
}
