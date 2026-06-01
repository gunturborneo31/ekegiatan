<?php

namespace Database\Seeders;

use App\Models\Bidang;
use App\Models\Kegiatan;
use App\Models\KegiatanStaff;
use App\Models\Laporan;
use App\Models\LaporanBiaya;
use App\Models\LaporanDokumentasi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $perencanaan = Bidang::updateOrCreate(
            ['kode_bidang' => 'REN'],
            ['nama_bidang' => 'Perencanaan', 'deskripsi' => 'Bidang perencanaan program dan evaluasi.']
        );
        $litbang = Bidang::updateOrCreate(
            ['kode_bidang' => 'LIT'],
            ['nama_bidang' => 'Litbang', 'deskripsi' => 'Bidang penelitian dan pengembangan daerah.']
        );
        $sekretariat = Bidang::updateOrCreate(
            ['kode_bidang' => 'SEK'],
            ['nama_bidang' => 'Sekretariat', 'deskripsi' => 'Bidang sekretariat dan dukungan administrasi.']
        );

        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@mahakamulu.test'],
            [
                'name' => 'Super Admin Mahulu',
                'nip' => '198001010001',
                'jabatan' => 'Super Admin',
                'password' => $password,
                'role' => 'super_admin',
                'bidang_id' => null,
                'phone' => '081100000001',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $pimpinan = User::updateOrCreate(
            ['email' => 'pimpinan@mahakamulu.test'],
            [
                'name' => 'Pimpinan Mahulu',
                'nip' => '198001010002',
                'jabatan' => 'Kepala Bappelitbangda',
                'password' => $password,
                'role' => 'pimpinan',
                'bidang_id' => $sekretariat->id,
                'phone' => '081100000002',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $adminPerencanaan = User::updateOrCreate(
            ['email' => 'admin.perencanaan@mahakamulu.test'],
            [
                'name' => 'Admin Perencanaan',
                'nip' => '198001010003',
                'jabatan' => 'Kasubbid Perencanaan',
                'password' => $password,
                'role' => 'admin_bidang',
                'bidang_id' => $perencanaan->id,
                'phone' => '081100000003',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $staffPerencanaan = User::updateOrCreate(
            ['email' => 'staff.perencanaan@mahakamulu.test'],
            [
                'name' => 'Staff Perencanaan',
                'nip' => '198001010004',
                'jabatan' => 'Analis Perencanaan',
                'password' => $password,
                'role' => 'staff',
                'bidang_id' => $perencanaan->id,
                'phone' => '081100000004',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $staffLitbang = User::updateOrCreate(
            ['email' => 'staff.litbang@mahakamulu.test'],
            [
                'name' => 'Staff Litbang',
                'nip' => '198001010005',
                'jabatan' => 'Peneliti Muda',
                'password' => $password,
                'role' => 'staff',
                'bidang_id' => $litbang->id,
                'phone' => '081100000005',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $kegiatan = Kegiatan::updateOrCreate(
            ['nama_kegiatan' => 'Monitoring Program Prioritas Daerah'],
            [
                'bidang_id' => $perencanaan->id,
                'deskripsi' => 'Monitoring dan evaluasi pelaksanaan program prioritas daerah.',
                'tanggal_berangkat' => now()->subDays(10)->toDateString(),
                'tanggal_pulang' => now()->subDays(8)->toDateString(),
                'lokasi' => 'Ujoh Bilang',
                'nomor_surat' => '090/SEK/VI/2026',
                'file_surat' => null,
                'file_surat_name' => 'placeholder-surat.pdf',
                'status' => 'selesai',
                'created_by' => $adminPerencanaan->id,
            ]
        );

        $suratPath = 'surat_kegiatan/' . $kegiatan->id . '/placeholder-surat.pdf';
        Storage::disk('private')->put($suratPath, 'Placeholder surat kegiatan Mahakam Ulu');
        $kegiatan->update(['file_surat' => $suratPath]);

        $kegiatanStaffAdmin = KegiatanStaff::updateOrCreate(
            ['kegiatan_id' => $kegiatan->id, 'user_id' => $adminPerencanaan->id],
            ['status_laporan' => 'disetujui', 'verified_at' => now()->subDays(6), 'verified_by' => $superAdmin->id]
        );
        $kegiatanStaffStaff = KegiatanStaff::updateOrCreate(
            ['kegiatan_id' => $kegiatan->id, 'user_id' => $staffPerencanaan->id],
            ['status_laporan' => 'submitted']
        );
        KegiatanStaff::updateOrCreate(
            ['kegiatan_id' => $kegiatan->id, 'user_id' => $pimpinan->id],
            ['status_laporan' => 'belum']
        );

        $laporanDisetujui = Laporan::updateOrCreate(
            ['kegiatan_staff_id' => $kegiatanStaffAdmin->id],
            ['kesimpulan' => 'Kegiatan berjalan lancar dan target monitoring tercapai.', 'submitted_at' => now()->subDays(7)]
        );
        $laporanSubmitted = Laporan::updateOrCreate(
            ['kegiatan_staff_id' => $kegiatanStaffStaff->id],
            ['kesimpulan' => 'Data monitoring telah terkumpul dan menunggu verifikasi.', 'submitted_at' => now()->subDays(5)]
        );

        $dokumentasiPath = 'laporan/' . $laporanDisetujui->id . '/dokumentasi/placeholder-dokumentasi.txt';
        Storage::disk('private')->put($dokumentasiPath, 'Placeholder dokumentasi laporan Mahakam Ulu');
        LaporanDokumentasi::updateOrCreate(
            ['laporan_id' => $laporanDisetujui->id, 'file_path' => $dokumentasiPath],
            [
                'file_name' => 'placeholder-dokumentasi.txt',
                'file_type' => 'txt',
                'file_size' => strlen('Placeholder dokumentasi laporan Mahakam Ulu'),
                'keterangan' => 'Foto dokumentasi placeholder',
                'urutan' => 1,
            ]
        );

        $buktiPath = 'laporan/' . $laporanDisetujui->id . '/biaya/placeholder-bukti.pdf';
        Storage::disk('private')->put($buktiPath, 'Placeholder bukti biaya Mahakam Ulu');
        LaporanBiaya::updateOrCreate(
            ['laporan_id' => $laporanDisetujui->id, 'jenis_biaya' => 'transport', 'keterangan' => 'Transport darat'],
            ['jumlah' => 350000, 'file_bukti_path' => $buktiPath, 'file_bukti_name' => 'placeholder-bukti.pdf', 'file_bukti_type' => 'pdf']
        );
        LaporanBiaya::updateOrCreate(
            ['laporan_id' => $laporanDisetujui->id, 'jenis_biaya' => 'makan', 'keterangan' => 'Konsumsi rapat'],
            ['jumlah' => 180000]
        );
        LaporanBiaya::updateOrCreate(
            ['laporan_id' => $laporanDisetujui->id, 'jenis_biaya' => 'penginapan', 'keterangan' => 'Penginapan 1 malam'],
            ['jumlah' => 450000]
        );
        $laporanDisetujui->recalculateTotals();

        LaporanBiaya::updateOrCreate(
            ['laporan_id' => $laporanSubmitted->id, 'jenis_biaya' => 'transport', 'keterangan' => 'Transport monitoring'],
            ['jumlah' => 200000]
        );
        $laporanSubmitted->recalculateTotals();

        Kegiatan::updateOrCreate(
            ['nama_kegiatan' => 'Rapat Koordinasi Inovasi Daerah'],
            [
                'bidang_id' => $litbang->id,
                'deskripsi' => 'Rapat koordinasi penguatan inovasi perangkat daerah.',
                'tanggal_berangkat' => now()->addDays(5)->toDateString(),
                'tanggal_pulang' => now()->addDays(6)->toDateString(),
                'lokasi' => 'Long Bagun',
                'nomor_surat' => '091/LIT/VI/2026',
                'status' => 'aktif',
                'created_by' => $superAdmin->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => $password,
                'role' => 'staff',
                'bidang_id' => $sekretariat->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
