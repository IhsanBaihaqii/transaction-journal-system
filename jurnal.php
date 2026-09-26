<?php
require 'koneksi.php';

// JOIN menghubungkan jurnal dengan bukti penjualan.
$sql = 'SELECT penjualan.id AS nomor, penjualan.tanggal, penjualan.pelanggan,
               jurnal.akun, jurnal.debit, jurnal.kredit
        FROM jurnal
        JOIN penjualan ON jurnal.penjualan_id = penjualan.id
        ORDER BY penjualan.id DESC, jurnal.id ASC';
$data = mysqli_query($koneksi, $sql);

$total = mysqli_fetch_assoc(mysqli_query(
    $koneksi,
    'SELECT COALESCE(SUM(debit),0) AS debit, COALESCE(SUM(kredit),0) AS kredit FROM jurnal'
));
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jurnal | SIA Dasar</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <aside class="sidebar">
        <div class="logo"><span>S</span><strong>SIA Dasar</strong></div>
        <p class="nav-label">MENU UTAMA</p><a class="nav" href="index.php">▦ &nbsp; Penjualan</a><a class="nav active" href="jurnal.php">≡ &nbsp; Jurnal otomatis</a>
        <div class="sidebar-note">Praktikum SIA<br>Manajemen Informatika</div>
    </aside>
    <main class="main">
        <header class="heading">
            <div><small>PERTEMUAN 5 · OTOMATISASI</small>
                <h1>Jurnal Umum</h1>
                <p>Hasil pencatatan otomatis dari transaksi penjualan tunai.</p>
            </div>
            <div class="heading-icon">≡</div>
        </header>
        <div class="stats two">
            <div class="stat"><span>Total Debit</span><strong><?php echo rupiah($total['debit']); ?></strong><small>Akun Kas</small></div>
            <div class="stat"><span>Total Kredit</span><strong><?php echo rupiah($total['kredit']); ?></strong><small>Akun Penjualan</small></div>
        </div>
        <section class="card list-card">
            <div class="card-title">
                <h2>Daftar Jurnal</h2>
                <p>Perhatikan hubungan ID penjualan dengan setiap baris jurnal.</p>
            </div>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>No. Bukti</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Akun</th>
                            <th class="right">Debit</th>
                            <th class="right">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $ada = false;
                        while ($baris = mysqli_fetch_assoc($data)) {
                            $ada = true; ?>
                            <tr>
                                <td><b>PNJ-<?php echo str_pad($baris['nomor'], 4, '0', STR_PAD_LEFT); ?></b></td>
                                <td><?php echo aman($baris['tanggal']); ?></td>
                                <td><?php echo aman($baris['pelanggan']); ?></td>
                                <td><?php echo aman($baris['akun']); ?></td>
                                <td class="right"><?php echo $baris['debit'] > 0 ? rupiah($baris['debit']) : '—'; ?></td>
                                <td class="right"><?php echo $baris['kredit'] > 0 ? rupiah($baris['kredit']) : '—'; ?></td>
                            </tr>
                        <?php }
                        if (!$ada) { ?><tr>
                                <td colspan="6" class="empty">Belum ada jurnal. Tambahkan penjualan terlebih dahulu.</td>
                            </tr><?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">TOTAL</th>
                            <th class="right"><?php echo rupiah($total['debit']); ?></th>
                            <th class="right"><?php echo rupiah($total['kredit']); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
        <div class="note"><b>Ingat:</b> setiap penjualan tunai menghasilkan dua baris jurnal dengan nominal sama. Total debit dan total kredit harus seimbang.</div>
        <footer>Praktikum Sistem Informasi Akuntansi · Semester 5</footer>
    </main>
</body>

</html>