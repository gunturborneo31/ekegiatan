<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Kegiatan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; }
        h1 { margin-bottom: 4px; }
        p { margin: 0 0 14px; color: #475569; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #e2e8f0; }
    </style>
</head>
<body>
    <h1>Rekap Kegiatan Mahakam Ulu</h1>
    <p>Dicetak pada {{ now()->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Kegiatan</th>
                <th>Bidang</th>
                <th>Periode</th>
                <th>Status</th>
                <th>Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kegiatans as $kegiatan)
                <tr>
                    <td>{{ $kegiatan->nama_kegiatan }}</td>
                    <td>{{ $kegiatan->bidang?->nama_bidang }}</td>
                    <td>{{ $kegiatan->tanggal_berangkat?->format('d/m/Y') }} - {{ $kegiatan->tanggal_pulang?->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($kegiatan->status) }}</td>
                    <td>Rp {{ number_format($kegiatan->total_biaya, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
