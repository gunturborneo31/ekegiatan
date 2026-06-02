<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Dashboard</p>
            <h2 class="text-2xl font-semibold text-slate-900">Ringkasan staff</h2>
        </div>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-2">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Tugas aktif</h3>
            <div class="mt-4 space-y-4">
                @forelse ($tugasAktif as $item)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $item->kegiatan?->nama_kegiatan }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $item->kegiatan?->bidang?->nama_bidang }} • {{ $item->status_label }}</p>
                            </div>
                            <a href="{{ route('tugas.show', $item) }}" class="text-sm font-medium text-emerald-600">Detail</a>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Tidak ada tugas aktif.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Riwayat laporan disetujui</h3>
            <div class="mt-4 space-y-4">
                @forelse ($riwayat as $item)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <p class="font-semibold text-slate-900">{{ $item->kegiatan?->nama_kegiatan }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $item->kegiatan?->bidang?->nama_bidang }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada laporan yang disetujui.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
