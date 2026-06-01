<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Modul tugas</p>
                <h2 class="text-2xl font-semibold text-slate-900">Detail penugasan</h2>
            </div>
            <a href="{{ route('tugas.laporan', $kegiatanStaff) }}" class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white">Buka laporan</a>
        </div>
    </x-slot>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <dl class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <div><dt class="text-sm text-slate-500">Kegiatan</dt><dd class="mt-1 text-lg font-semibold text-slate-900">{{ $kegiatanStaff->kegiatan?->nama_kegiatan }}</dd></div>
            <div><dt class="text-sm text-slate-500">Bidang</dt><dd class="mt-1 text-slate-700">{{ $kegiatanStaff->kegiatan?->bidang?->nama_bidang }}</dd></div>
            <div><dt class="text-sm text-slate-500">Status laporan</dt><dd class="mt-1 text-slate-700">{{ $kegiatanStaff->status_label }}</dd></div>
            <div><dt class="text-sm text-slate-500">Tanggal berangkat</dt><dd class="mt-1 text-slate-700">{{ $kegiatanStaff->kegiatan?->tanggal_berangkat?->format('d M Y') }}</dd></div>
            <div><dt class="text-sm text-slate-500">Tanggal pulang</dt><dd class="mt-1 text-slate-700">{{ $kegiatanStaff->kegiatan?->tanggal_pulang?->format('d M Y') }}</dd></div>
            <div><dt class="text-sm text-slate-500">Lokasi</dt><dd class="mt-1 text-slate-700">{{ $kegiatanStaff->kegiatan?->lokasi }}</dd></div>
            <div class="md:col-span-2 xl:col-span-3"><dt class="text-sm text-slate-500">Deskripsi</dt><dd class="mt-1 text-slate-700">{{ $kegiatanStaff->kegiatan?->deskripsi ?: '-' }}</dd></div>
            @if ($kegiatanStaff->catatan_revisi)
                <div class="md:col-span-2 xl:col-span-3"><dt class="text-sm text-slate-500">Catatan revisi</dt><dd class="mt-1 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800">{{ $kegiatanStaff->catatan_revisi }}</dd></div>
            @endif
        </dl>
    </div>
</x-app-layout>
