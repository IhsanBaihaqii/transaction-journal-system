<?php
require '../config/koneksi.php';
require '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../beli.php');
    exit;
}

$tanggal = $_POST['tanggal'] ?? '';
$pemasok = trim($_POST['pemasok'] ?? '');
$keterangan = trim($_POST['keterangan'] ?? '');
$jumlah = $_POST['jumlah'] ?? '';

$valid =
    validasiTanggal($tanggal)
    && $pemasok !== ''
    && $keterangan !== ''
    && strlen($pemasok) <= 100
    && strlen($keterangan) <= 200
    && ctype_digit((string) $jumlah)
    && (float) $jumlah > 0
    && (float) $jumlah <= 9999999999;

if (!$valid) {
    header(
        'Location: ../beli.php?pesan=gagal'
    );
    exit;
}

mysqli_begin_transaction($koneksi);

try {
    $sql = '
        INSERT INTO pembelian
        (
            tanggal,
            pemasok,
            keterangan,
            jumlah
        )
        VALUES (?, ?, ?, ?)
    ';

    $stmt = mysqli_prepare(
        $koneksi,
        $sql
    );

    mysqli_stmt_bind_param(
        $stmt,
        'sssd',
        $tanggal,
        $pemasok,
        $keterangan,
        $jumlah
    );

    mysqli_stmt_execute($stmt);

    $idPembelian = mysqli_insert_id($koneksi);

    $sqlJurnal = '
        INSERT INTO jurnal
        (
            jenis_transaksi,
            referensi_id,
            tanggal,
            akun,
            debit,
            kredit
        )
        VALUES (?, ?, ?, ?, ?, ?)
    ';

    $stmtJurnal = mysqli_prepare(
        $koneksi,
        $sqlJurnal
    );

    $jenis = 'pembelian';

    // Pembelian bertambah pada sisi debit

    $akun = 'Pembelian';
    $debit = $jumlah;
    $kredit = 0;

    mysqli_stmt_bind_param(
        $stmtJurnal,
        'sissdd',
        $jenis,
        $idPembelian,
        $tanggal,
        $akun,
        $debit,
        $kredit
    );

    mysqli_stmt_execute($stmtJurnal);

    // Kas berkurang pada sisi kredit

    $akun = 'Kas';
    $debit = 0;
    $kredit = $jumlah;

    mysqli_stmt_execute($stmtJurnal);
    mysqli_commit($koneksi);
    header(
        'Location: ../beli.php?pesan=berhasil'
    );
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    header(
        'Location: ../beli.php?pesan=gagal'
    );
}

exit;
