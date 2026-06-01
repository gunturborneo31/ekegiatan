<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Modul tugas</p>
            <h2 class="text-2xl font-semibold text-slate-900">Daftar penugasan</h2>
        </div>
    </x-slot>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-slate-500">
                    <tr><th class="px-4 py-3 font-medium">Kegiatan</th><th class="px-4 py-3 font-medium">Bidang</th><th class="px-4 py-3 font-medium">Status laporan</th><th class="px-4 py-3 font-medium">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($list as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-medium text-slate-800">{{ $item->kegiatan?->nama_kegiatan }}</p>
                                <p class="text-slate-500">{{ $item->kegiatan?->tanggal_berangkat?->format('d M Y') }} - {{ $item->kegiatan?->tanggal_pulang?->format('d M Y') }}</p>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $item->kegiatan?->bidang?->nama_bidang ?: '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $item->status_label }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('tugas.show', $item) }}" class="text-emerald-600">Detail</a>
                                    <a href="{{ route('tugas.laporan', $item) }}" class="text-slate-900">Buka laporan</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-slate-500">Belum ada penugasan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $list->links() }}</div>
    </div>
</x-app-layout>
