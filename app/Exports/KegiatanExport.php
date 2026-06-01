<?php

namespace App\Exports;

use App\Models\Kegiatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KegiatanExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(private readonly Kegiatan $kegiatan)
    {
    }

    public function collection()
    {
        return $this->kegiatan->kegiatanStaff()
            ->with(['user', 'laporan'])
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Kegiatan',
            'Nama Kegiatan',
            'Bidang',
            'Staff',
            'NIP',
            'Status Laporan',
            'Tanggal Berangkat',
            'Tanggal Pulang',
            'Lokasi',
            'Kesimpulan',
            'Total Transport',
            'Total Makan',
            'Total Penginapan',
            'Total Keseluruhan',
        ];
    }

    public function map($kegiatanStaff): array
    {
        $laporan = $kegiatanStaff->laporan;

        return [
            $this->kegiatan->id,
            $this->kegiatan->nama_kegiatan,
            $this->kegiatan->bidang?->nama_bidang,
            $kegiatanStaff->user?->name,
            $kegiatanStaff->user?->nip,
            $kegiatanStaff->status_label,
            optional($this->kegiatan->tanggal_berangkat)->format('d-m-Y'),
            optional($this->kegiatan->tanggal_pulang)->format('d-m-Y'),
            $this->kegiatan->lokasi,
            $laporan?->kesimpulan,
            (float) ($laporan?->total_transport ?? 0),
            (float) ($laporan?->total_makan ?? 0),
            (float) ($laporan?->total_penginapan ?? 0),
            (float) ($laporan?->total_keseluruhan ?? 0),
        ];
    }
}
