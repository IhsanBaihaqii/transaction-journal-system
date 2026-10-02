
<?php

$halamanAktif = $halamanAktif ?? '';
?>

<aside class="sidebar">

    <div class="logo">
        <span>S</span>
        <strong>SIA Dasar</strong>
    </div>

    <p class="nav-label">
        MENU UTAMA
    </p>

    <a
        class="nav <?php echo $halamanAktif === 'penjualan' ? 'active' : ''; ?>"
        href="index.php">
        ▦ &nbsp; Penjualan
    </a>

    <a
        class="nav <?php echo $halamanAktif === 'pembelian' ? 'active' : ''; ?>"
        href="beli.php">
        ▣ &nbsp; Pembelian
    </a>

    <a
        class="nav <?php echo $halamanAktif === 'jurnal' ? 'active' : ''; ?>"
        href="jurnal.php">
        ≡ &nbsp; Jurnal Umum
    </a>

    <div class="sidebar-note">
        Praktikum SIA
        <br>
        Manajemen Informatika
    </div>

</aside>