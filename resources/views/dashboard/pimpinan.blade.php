<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Dashboard</p>
            <h2 class="text-2xl font-semibold text-slate-900">Ringkasan pimpinan</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Kegiatan aktif</p><p class="mt-3 text-3xl font-semibold">{{ $totalAktif }}</p></div>
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Laporan disetujui bulan ini</p><p class="mt-3 text-3xl font-semibold">{{ $totalDisetujui }}</p></div>
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Menunggu verifikasi</p><p class="mt-3 text-3xl font-semibold">{{ $totalPending }}</p></div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1fr_1fr]">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Biaya per bidang</h3>
                <div class="mt-4 space-y-4">
                    @forelse ($biayaPerBidang as $item)
                        <div class="flex items-center justify-between rounded-2xl border border-slate-100 px-4 py-3 text-sm">
                            <span class="font-medium text-slate-700">{{ $item['nama'] }}</span>
                            <span class="font-semibold text-slate-900">Rp {{ number_format($item['total'], 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada data biaya.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Kegiatan terbaru</h3>
                <div class="mt-4 space-y-4">
                    @forelse ($recentKegiatan as $kegiatan)
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="font-semibold text-slate-900">{{ $kegiatan->nama_kegiatan }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $kegiatan->bidang?->nama_bidang }} • {{ ucfirst($kegiatan->status) }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada kegiatan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
