<?php

namespace App\Helpers;

class WhatsappHelper
{
    public static function generateLink(string $pesan, string $nomorTujuan = ''): string
    {
        $nomor = preg_replace('/[^0-9]/', '', $nomorTujuan);
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }
        $encodedPesan = rawurlencode($pesan);
        return "https://wa.me/{$nomor}?text={$encodedPesan}";
    }

    public static function pesanPenugasan(string $namaStaff, string $namaKegiatan, string $tglBerangkat, string $tglPulang, string $lokasi): string
    {
        return "Yth. {$namaStaff}, Anda ditugaskan dalam kegiatan *{$namaKegiatan}* pada tanggal {$tglBerangkat} s.d {$tglPulang} di {$lokasi}. Mohon isi laporan setelah kegiatan selesai melalui aplikasi e-Kegiatan Bappelitbangda Mahakam Ulu.";
    }

    public static function pesanDisetujui(string $namaStaff, string $namaKegiatan): string
    {
        return "Yth. {$namaStaff}, laporan kegiatan Anda untuk *{$namaKegiatan}* telah *DISETUJUI* oleh Admin Bidang. Terima kasih.";
    }

    public static function pesanRevisi(string $namaStaff, string $namaKegiatan, string $catatanRevisi): string
    {
        return "Yth. {$namaStaff}, laporan kegiatan Anda untuk *{$namaKegiatan}* memerlukan *REVISI*. Catatan: {$catatanRevisi}. Mohon segera perbaiki melalui aplikasi e-Kegiatan.";
    }
}
