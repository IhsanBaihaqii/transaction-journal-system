<?php
require 'koneksi.php';

// Query ringkasan. COALESCE mengubah hasil kosong menjadi 0.
$ringkasan = mysqli_fetch_assoc(mysqli_query(
    $koneksi,
    'SELECT COUNT(*) AS banyak, COALESCE(SUM(jumlah), 0) AS total FROM penjualan'
));
$data = mysqli_query($koneksi, 'SELECT * FROM penjualan ORDER BY id DESC');
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Penjualan | SIA Dasar</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <aside class="sidebar">
        <div class="logo"><span>S</span><strong>SIA Dasar</strong></div>
        <p class="nav-label">MENU UTAMA</p>
        <a class="nav active" href="index.php">▦ &nbsp; Penjualan</a>
        <a class="nav" href="jurnal.php">≡ &nbsp; Jurnal otomatis</a>
        <div class="sidebar-note">Praktikum SIA<br>Manajemen Informatika</div>
    </aside>
    <main class="main">
        <header class="heading">
            <div><small>PERTEMUAN 5 · OTOMATISASI</small>
                <h1>Penjualan Tunai</h1>
                <p>Satu transaksi menghasilkan jurnal debit dan kredit secara otomatis.</p>
            </div>
            <div class="heading-icon">↗</div>
        </header>

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil') { ?>
            <div class="alert success">Penjualan berhasil disimpan. Dua baris jurnal telah dibuat.</div>
        <?php } ?>
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal') { ?>
            <div class="alert error">Data belum tersimpan. Periksa tanggal, pelanggan, dan jumlah.</div>
        <?php } ?>

        <div class="stats">
            <div class="stat"><span>Total Penjualan</span><strong><?php echo rupiah($ringkasan['total']); ?></strong><small>Seluruh transaksi tersimpan</small></div>
            <div class="stat"><span>Jumlah Transaksi</span><strong><?php echo (int)$ringkasan['banyak']; ?></strong><small>Bukti penjualan yang tercatat</small></div>
            <div class="stat accent"><span>Aturan Jurnal</span><strong>Debit = Kredit</strong><small>Kas dan Penjualan</small></div>
        </div>

        <div class="columns">
            <section class="card">
                <div class="card-title">
                    <h2>Tambah Penjualan</h2>
                    <p>Masukkan transaksi tunai sederhana.</p>
                </div>
                <form action="simpan/penjualan.php" method="post" onsubmit="this.querySelector('button').disabled=true">
                    <label>Tanggal <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required></label>
                    <label>Nama Pelanggan <input type="text" name="pelanggan" maxlength="100" placeholder="Contoh: Andi" required></label>
                    <label>Keterangan <input type="text" name="keterangan" maxlength="200" placeholder="Contoh: Penjualan tunai" required></label>
                    <label>Jumlah (Rp) <input type="number" name="jumlah" min="1" max="9999999999" step="1" placeholder="250000" required></label>
                    <button type="submit">Simpan dan Buat Jurnal &nbsp; →</button>
                </form>
            </section>
            <section class="card lesson">
                <div class="lesson-tag">CARA KERJA PROGRAM</div>
                <h2>Input satu kali,<br>jurnal dua baris</h2>
                <ol>
                    <li>Isi tanggal, pelanggan, dan jumlah.</li>
                    <li>Data masuk ke tabel <code>penjualan</code>.</li>
                    <li>Sistem mencatat Kas di sisi debit.</li>
                    <li>Sistem mencatat Penjualan di sisi kredit.</li>
                </ol>
                <div class="example"><b>Contoh: Rp250.000</b><span>Kas (Debit) Rp250.000</span><span>Penjualan (Kredit) Rp250.000</span></div>
            </section>
        </div>

        <section class="card list-card">
            <div class="card-title">
                <h2>Daftar Penjualan</h2>
                <p>Nomor bukti menggunakan ID penjualan.</p>
            </div>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>No. Bukti</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Keterangan</th>
                            <th class="right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($baris = mysqli_fetch_assoc($data)) { ?>
                            <tr>
                                <td><b>PNJ-<?php echo str_pad($baris['id'], 4, '0', STR_PAD_LEFT); ?></b></td>
                                <td><?php echo aman($baris['tanggal']); ?></td>
                                <td><?php echo aman($baris['pelanggan']); ?></td>
                                <td><?php echo aman($baris['keterangan']); ?></td>
                                <td class="right"><b><?php echo rupiah($baris['jumlah']); ?></b></td>
                            </tr>
                        <?php } ?>
                        <?php if ((int)$ringkasan['banyak'] == 0) { ?><tr>
                                <td colspan="5" class="empty">Belum ada penjualan. Isi formulir di atas untuk memulai.</td>
                            </tr><?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
        <footer>Praktikum Sistem Informasi Akuntansi · Semester 5</footer>
    </main>
</body>

</html>