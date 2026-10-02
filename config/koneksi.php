<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'sia_pemula';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $koneksi = mysqli_connect(
        $host,
        $user,
        $password,
        $database
    );

    mysqli_set_charset($koneksi, 'utf8mb4');
} catch (mysqli_sql_exception $e) {
    die('Koneksi database gagal. Periksa konfigurasi database.');
}
