<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Kegiatan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; }
        h1 { margin-bottom: 4px; }
        h2 { margin-top: 20px; margin-bottom: 8px; }
        p { margin: 0 0 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #e2e8f0; }
        .meta td { border: none; padding: 2px 0; }
    </style>
</head>
<body>
    <h1>{{ $kegiatan->nama_kegiatan }}</h1>
    <p>Mahakam Ulu • e-Kegiatan</p>

    <table class="meta">
        <tr><td width="160">Bidang</td><td>: {{ $kegiatan->bidang?->nama_bidang }}</td></tr>
        <tr><td>Periode</td><td>: {{ $kegiatan->tanggal_berangkat?->format('d/m/Y') }} - {{ $kegiatan->tanggal_pulang?->format('d/m/Y') }}</td></tr>
        <tr><td>Lokasi</td><td>: {{ $kegiatan->lokasi }}</td></tr>
        <tr><td>Status</td><td>: {{ ucfirst($kegiatan->status) }}</td></tr>
        <tr><td>Deskripsi</td><td>: {{ $kegiatan->deskripsi ?: '-' }}</td></tr>
    </table>

    <h2>Staff dan laporan</h2>
    <table>
        <thead>
            <tr>
                <th>Staff</th>
                <th>Status</th>
                <th>Kesimpulan</th>
                <th>Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kegiatan->kegiatanStaff as $item)
                <tr>
                    <td>{{ $item->user?->name }}</td>
                    <td>{{ $item->status_label }}</td>
                    <td>{{ $item->laporan?->kesimpulan ?: '-' }}</td>
                    <td>Rp {{ number_format($item->laporan?->total_keseluruhan ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Belum ada staff yang ditugaskan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
