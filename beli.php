<?php

require 'config/koneksi.php';
require 'includes/functions.php';

$halamanAktif = 'pembelian';

$ringkasan = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        '
        SELECT
            COUNT(*) AS banyak,
            COALESCE(SUM(jumlah), 0) AS total
        FROM pembelian
        '
    )
);

$data = mysqli_query(
    $koneksi,
    '
    SELECT *
    FROM pembelian
    ORDER BY id DESC
    '
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
        Pembelian | SIA Dasar
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
                    Pembelian Tunai
                </h1>
                <p>
                    Catat pembelian dan buat jurnal otomatis.
                </p>
            </div>
            <div class="heading-icon">
                ↓
            </div>
        </header>

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'berhasil') { ?>
            <div class="alert success">
                Pembelian berhasil disimpan dan jurnal otomatis telah dibuat.
            </div>
        <?php } ?>

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'gagal') { ?>
            <div class="alert error">
                Pembelian gagal disimpan. Periksa kembali data.
            </div>
        <?php } ?>

        <div class="stats">
            <div class="stat">
                <span>
                    Total Pembelian
                </span>
                <strong>
                    <?php echo rupiah($ringkasan['total']); ?>
                </strong>
                <small>
                    Seluruh transaksi pembelian
                </small>
            </div>
            <div class="stat">
                <span>
                    Jumlah Transaksi
                </span>
                <strong>
                    <?php echo (int) $ringkasan['banyak']; ?>
                </strong>
                <small>
                    Bukti pembelian tercatat
                </small>
            </div>
            <div class="stat accent">
                <span>
                    Aturan Jurnal
                </span>
                <strong>
                    Debit = Kredit
                </strong>
                <small>
                    Pembelian dan Kas
                </small>
            </div>
        </div>
        <div class="columns">
            <section class="card">
                <div class="card-title">
                    <h2>
                        Tambah Pembelian
                    </h2>
                    <p>
                        Masukkan transaksi pembelian tunai.
                    </p>
                </div>

                <form
                    action="simpan/pembelian.php"
                    method="post"
                    onsubmit="this.querySelector('button').disabled=true">
                    <label>
                        Tanggal
                        <input
                            type="date"
                            name="tanggal"
                            value="<?php echo date('Y-m-d'); ?>"
                            required>
                    </label>
                    <label>
                        Nama Pemasok
                        <input
                            type="text"
                            name="pemasok"
                            maxlength="100"
                            placeholder="Contoh: PT Maju Jaya"
                            required>
                    </label>
                    <label>
                        Keterangan
                        <input
                            type="text"
                            name="keterangan"
                            maxlength="200"
                            placeholder="Contoh: Pembelian barang tunai"
                            required>
                    </label>
                    <label>
                        Jumlah (Rp)
                        <input
                            type="number"
                            name="jumlah"
                            min="1"
                            max="9999999999"
                            step="1"
                            placeholder="500000"
                            required>
                    </label>
                    <button type="submit">
                        Simpan dan Buat Jurnal →
                    </button>
                </form>
            </section>
            <section class="card lesson">
                <div class="lesson-tag">
                    JURNAL PEMBELIAN
                </div>
                <h2>
                    Pembelian tunai
                </h2>
                <ol>
                    <li>
                        Masukkan transaksi pembelian.
                    </li>
                    <li>
                        Data masuk ke tabel pembelian.
                    </li>
                    <li>
                        Pembelian dicatat pada sisi debit.
                    </li>
                    <li>
                        Kas dicatat pada sisi kredit.
                    </li>
                </ol>
                <div class="example">
                    <b>
                        Contoh Rp500.000
                    </b>
                    <span>
                        Pembelian (Debit) Rp500.000
                    </span>
                    <span>
                        Kas (Kredit) Rp500.000
                    </span>
                </div>
            </section>
        </div>

        <section class="card list-card">
            <div class="card-title">
                <h2>
                    Daftar Pembelian
                </h2>
                <p>
                    Seluruh transaksi pembelian tunai.
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
                                Pemasok
                            </th>
                            <th>
                                Keterangan
                            </th>
                            <th class="right">
                                Jumlah
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($baris = mysqli_fetch_assoc($data)) { ?>
                            <tr>
                                <td>
                                    <b>
                                        <?php echo nomorBukti('pembelian', $baris['id']); ?>
                                    </b>
                                </td>
                                <td>
                                    <?php echo aman($baris['tanggal']); ?>
                                </td>
                                <td>
                                    <?php echo aman($baris['pemasok']); ?>
                                </td>
                                <td>
                                    <?php echo aman($baris['keterangan']); ?>
                                </td>
                                <td class="right">
                                    <b>
                                        <?php echo rupiah($baris['jumlah']); ?>
                                    </b>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php if ((int) $ringkasan['banyak'] === 0) { ?>
                            <tr>
                                <td
                                    colspan="5"
                                    class="empty">
                                    Belum ada transaksi pembelian.
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </section>
        <footer>
            Praktikum Sistem Informasi Akuntansi · Semester 5
        </footer>

    </main>

</body>

</html>