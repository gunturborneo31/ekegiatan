<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Modul kegiatan</p>
                <h2 class="text-2xl font-semibold text-slate-900">Detail kegiatan</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('kegiatan.edit', $kegiatan) }}" class="rounded-2xl border border-slate-300 px-5 py-3 font-semibold text-slate-700">Edit</a>
                <a href="{{ route('rekap.kegiatan.excel', $kegiatan) }}" class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white">Export Excel</a>
                <a href="{{ route('rekap.kegiatan.pdf', $kegiatan) }}" class="rounded-2xl bg-slate-900 px-5 py-3 font-semibold text-white">Export PDF</a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <dl class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                <div><dt class="text-sm text-slate-500">Nama kegiatan</dt><dd class="mt-1 text-lg font-semibold text-slate-900">{{ $kegiatan->nama_kegiatan }}</dd></div>
                <div><dt class="text-sm text-slate-500">Bidang</dt><dd class="mt-1 text-slate-700">{{ $kegiatan->bidang?->nama_bidang }}</dd></div>
                <div><dt class="text-sm text-slate-500">Status</dt><dd class="mt-1 text-slate-700">{{ ucfirst($kegiatan->status) }}</dd></div>
                <div><dt class="text-sm text-slate-500">Tanggal</dt><dd class="mt-1 text-slate-700">{{ $kegiatan->tanggal_berangkat?->format('d M Y') }} - {{ $kegiatan->tanggal_pulang?->format('d M Y') }}</dd></div>
                <div><dt class="text-sm text-slate-500">Lokasi</dt><dd class="mt-1 text-slate-700">{{ $kegiatan->lokasi }}</dd></div>
                <div><dt class="text-sm text-slate-500">Nomor surat</dt><dd class="mt-1 text-slate-700">{{ $kegiatan->nomor_surat ?: '-' }}</dd></div>
                <div class="md:col-span-2 xl:col-span-3"><dt class="text-sm text-slate-500">Deskripsi</dt><dd class="mt-1 text-slate-700">{{ $kegiatan->deskripsi ?: '-' }}</dd></div>
            </dl>
            @if ($kegiatan->file_surat)
                <div class="mt-6 rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    File surat: <a href="{{ route('storage.file', ['path' => $kegiatan->file_surat]) }}" class="font-semibold text-emerald-600">{{ $kegiatan->file_surat_name ?: 'Lihat file' }}</a>
                </div>
            @endif
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Daftar staff</h3>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-slate-500">
                        <tr><th class="px-4 py-3 font-medium">Nama</th><th class="px-4 py-3 font-medium">Status laporan</th><th class="px-4 py-3 font-medium">Kesimpulan</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($kegiatan->kegiatanStaff as $item)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $item->user?->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $item->status_label }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $item->laporan?->kesimpulan ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-6 text-center text-slate-500">Belum ada staff yang ditugaskan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
