<?php

require 'config/koneksi.php';
require 'includes/functions.php';

$halamanAktif = 'jurnal';

$sql = '
    SELECT
        id,
        jenis_transaksi,
        referensi_id,
        tanggal,
        akun,
        debit,
        kredit
    FROM jurnal
    ORDER BY tanggal DESC, id DESC
';

$data = mysqli_query(
    $koneksi,
    $sql
);

$total = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        '
        SELECT
            COALESCE(SUM(debit), 0) AS debit,
            COALESCE(SUM(kredit), 0) AS kredit
        FROM jurnal
        '
    )
);

?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">
    <title>
        Jurnal Umum | SIA Dasar
    </title>
    <link
        rel="stylesheet"
        href="assets/style.css">
</head>

<body>
    <?php require 'includes/sidebar.php'; ?>
    <main class="main">
        <header class="heading">
            <div>
                <small>
                    PERTEMUAN 5 · OTOMATISASI
                </small>
                <h1>
                    Jurnal Umum
                </h1>
                <p>
                    Hasil pencatatan otomatis transaksi
                    penjualan dan pembelian.
                </p>
            </div>
            <div class="heading-icon">
                ≡
            </div>
        </header>
        <div class="stats two">
            <div class="stat">
                <span>
                    Total Debit
                </span>
                <strong>
                    <?php echo rupiah($total['debit']); ?>
                </strong>
                <small>
                    Seluruh akun debit
                </small>
            </div>
            <div class="stat">
                <span>
                    Total Kredit
                </span>
                <strong>
                    <?php echo rupiah($total['kredit']); ?>
                </strong>
                <small>
                    Seluruh akun kredit
                </small>
            </div>
        </div>

        <section class="card list-card">
            <div class="card-title">
                <h2>
                    Daftar Jurnal
                </h2>
                <p>
                    Jurnal otomatis dari setiap transaksi.
                </p>
            </div>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>
                                No. Bukti
                            </th>
                            <th>
                                Tanggal
                            </th>
                            <th>
                                Jenis
                            </th>
                            <th>
                                Akun
                            </th>
                            <th class="right">
                                Debit
                            </th>
                            <th class="right">
                                Kredit
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ada = false;
                        while ($baris = mysqli_fetch_assoc($data)) {
                            $ada = true;
                        ?>
                            <tr>
                                <td>
                                    <b>
                                        <?php
                                        echo nomorBukti(
                                            $baris['jenis_transaksi'],
                                            $baris['referensi_id']
                                        );
                                        ?>
                                    </b>
                                </td>
                                <td>
                                    <?php echo aman($baris['tanggal']); ?>
                                </td>
                                <td>
                                    <?php echo ucfirst(aman($baris['jenis_transaksi'])); ?>
                                </td>
                                <td>
                                    <?php echo aman($baris['akun']); ?>
                                </td>
                                <td class="right">
                                    <?php
                                    echo $baris['debit'] > 0
                                        ? rupiah($baris['debit'])
                                        : '—';
                                    ?>
                                </td>
                                <td class="right">
                                    <?php
                                    echo $baris['kredit'] > 0
                                        ? rupiah($baris['kredit'])
                                        : '—';
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php if (!$ada) { ?>
                            <tr>
                                <td
                                    colspan="6"
                                    class="empty">
                                    Belum ada jurnal.
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">
                                TOTAL
                            </th>
                            <th class="right">
                                <?php echo rupiah($total['debit']); ?>
                            </th>
                            <th class="right">
                                <?php echo rupiah($total['kredit']); ?>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>

        <div class="note">
            <b>Ingat:</b>
            setiap transaksi harus menghasilkan jumlah
            debit dan kredit yang sama.
        </div>
        <footer>
            Praktikum Sistem Informasi Akuntansi · Semester 5
        </footer>
    </main>

</html>