<?php

namespace App\Exports;

use App\Models\Kegiatan;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RekapExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(
        private readonly array $filters,
        private readonly User $user,
    ) {
    }

    public function collection()
    {
        $query = Kegiatan::with(['bidang', 'kegiatanStaff.laporan']);

        if ($this->user->isAdminBidang()) {
            $query->where('bidang_id', $this->user->bidang_id);
        }

        if ($this->filters['bidang_id'] ?? null) {
            $query->where('bidang_id', $this->filters['bidang_id']);
        }

        if ($this->filters['tahun'] ?? null) {
            $query->whereYear('tanggal_berangkat', $this->filters['tahun']);
        } else {
            $query->whereYear('tanggal_berangkat', now()->year);
        }

        if ($this->filters['bulan'] ?? null) {
            $query->whereMonth('tanggal_berangkat', $this->filters['bulan']);
        }

        if ($this->filters['status'] ?? null) {
            $query->where('status', $this->filters['status']);
        }

        if ($this->filters['search'] ?? null) {
            $query->where('nama_kegiatan', 'like', '%' . $this->filters['search'] . '%');
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Kegiatan',
            'Bidang',
            'Tanggal Berangkat',
            'Tanggal Pulang',
            'Lokasi',
            'Status',
            'Jumlah Staff',
            'Laporan Disetujui',
            'Total Biaya',
        ];
    }

    public function map($kegiatan): array
    {
        $laporanDisetujui = $kegiatan->kegiatanStaff->where('status_laporan', 'disetujui')->count();
        $totalBiaya = $kegiatan->kegiatanStaff->sum(fn ($item) => (float) ($item->laporan?->total_keseluruhan ?? 0));

        return [
            $kegiatan->id,
            $kegiatan->nama_kegiatan,
            $kegiatan->bidang?->nama_bidang,
            optional($kegiatan->tanggal_berangkat)->format('d-m-Y'),
            optional($kegiatan->tanggal_pulang)->format('d-m-Y'),
            $kegiatan->lokasi,
            ucfirst($kegiatan->status),
            $kegiatan->kegiatanStaff->count(),
            $laporanDisetujui,
            $totalBiaya,
        ];
    }
}
