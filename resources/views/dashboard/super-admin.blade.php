<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Dashboard</p>
            <h2 class="text-2xl font-semibold text-slate-900">Ringkasan super admin</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Total user aktif</p><p class="mt-3 text-3xl font-semibold">{{ $totalUser }}</p></div>
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Total bidang</p><p class="mt-3 text-3xl font-semibold">{{ $totalBidang }}</p></div>
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Kegiatan bulan ini</p><p class="mt-3 text-3xl font-semibold">{{ $totalKegiatan }}</p></div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Kegiatan per bulan</h3>
                <div class="mt-6 space-y-4">
                    @forelse ($kegiatanPerBulan as $item)
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm text-slate-600">
                                <span>Bulan {{ $item->bulan }}</span>
                                <span>{{ $item->total }} kegiatan</span>
                            </div>
                            <div class="h-3 rounded-full bg-slate-100">
                                <div class="h-3 rounded-full bg-emerald-500" style="width: {{ max(8, $item->total * 10) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada data grafik tahun ini.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Kegiatan terbaru</h3>
                <div class="mt-4 space-y-4">
                    @forelse ($recentKegiatan as $kegiatan)
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="font-semibold text-slate-900">{{ $kegiatan->nama_kegiatan }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $kegiatan->bidang?->nama_bidang }} • {{ $kegiatan->pembuat?->name }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada kegiatan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
