# SIA Dasar: Praktik Penjualan Tunai

Versi pemula untuk mata kuliah Sistem Informasi Akuntansi semester 5. Hanya ada **dua halaman aplikasi**, yaitu Penjualan dan Jurnal. Kode memakai PHP native, MySQLi, HTML, dan CSS. Tidak ada framework, login, akun pengguna, atau pengaturan yang rumit.

## Cara menjalankan di XAMPP

1. Ekstrak folder `transaction-journal-system` ke `C:\xampp\htdocs\transaction-journal-system`.
2. Jalankan Apache dan MySQL di XAMPP.
3. Buka `http://localhost/phpmyadmin`, pilih **Import**, lalu pilih `database.sql`.
4. Bila username atau password MySQL berbeda, ubah `koneksi.php`.
5. Buka `http://localhost/transaction-journal-system/`.

Gunakan PHP 7.4 ke atas serta MySQL atau MariaDB. Impor SQL dilakukan **sekali** pada database kosong.

## Urutan belajar kode

| File               | Yang dipelajari                                              |
| ------------------ | ------------------------------------------------------------ |
| `database.sql`     | Dua tabel dan hubungan melalui `penjualan_id`                |
| `koneksi.php`      | Menghubungkan PHP dengan MySQL; fungsi tampilan sederhana    |
| `index.php`        | Form HTML, SELECT, perulangan `while`, dan tabel data        |
| `simpan.php`       | POST, validasi, INSERT, ID terakhir, dan dua jurnal otomatis |
| `jurnal.php`       | JOIN, SUM, debit, dan kredit                                 |
| `assets/style.css` | Warna, tata letak, dan tampilan responsif                    |

## Contoh pengujian kelas

1. Simpan penjualan untuk **Andi** sebesar **Rp250.000**.
2. Simpan penjualan untuk **Bina** sebesar **Rp175.000**.
3. Buka halaman Jurnal. Ada **empat baris**: masing-masing transaksi menghasilkan debit Kas dan kredit Penjualan.
4. Total debit dan kredit masing-masing **Rp425.000**.
5. Coba isi nominal 0. Form menolak data tersebut.

## Penjelasan singkat otomatisasi

Di `simpan.php`, satu penjualan masuk ke tabel `penjualan`. ID yang baru dibuat kemudian dipakai dua kali pada tabel `jurnal`. Baris pertama berisi akun Kas pada debit, baris kedua berisi akun Penjualan pada kredit. Semua perintah disimpan dalam satu transaksi database; jika satu langkah gagal, seluruhnya dibatalkan.

**Batas latihan:** aplikasi sengaja tidak menyediakan login, edit, hapus, pajak, retur, atau stok. Gunakan untuk latihan lokal di kelas, bukan pembukuan perusahaan atau situs publik.
