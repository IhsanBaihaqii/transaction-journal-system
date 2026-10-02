<?php

function aman($teks)
{
    return htmlspecialchars(
        (string) $teks,
        ENT_QUOTES,
        'UTF-8'
    );
}

function rupiah($angka)
{
    return 'Rp' . number_format((float) $angka, 0, ',', '.');
}

function validasiTanggal($tanggal)
{
    $tanggalBenar = DateTime::createFromFormat(
        '!Y-m-d',
        $tanggal
    );

    return $tanggalBenar
        && $tanggalBenar->format('Y-m-d') === $tanggal;
}

function nomorBukti($jenis, $id)
{
    $prefix = $jenis === 'pembelian'
        ? 'PMB'
        : 'PNJ';

    return $prefix . '-' . str_pad(
        $id,
        4,
        '0',
        STR_PAD_LEFT
    );
}
