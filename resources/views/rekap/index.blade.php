<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Modul rekap</p>
                <h2 class="text-2xl font-semibold text-slate-900">Rekap kegiatan</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('rekap.export-excel', request()->query()) }}" class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white">Export Excel</a>
                <a href="{{ route('rekap.export-pdf', request()->query()) }}" class="rounded-2xl bg-slate-900 px-5 py-3 font-semibold text-white">Export PDF</a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <form method="GET" class="grid gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 md:grid-cols-2 xl:grid-cols-5">
            <select name="bidang_id" class="rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Semua bidang</option>
                @foreach ($bidangs as $bidang)
                    <option value="{{ $bidang->id }}" @selected((string) request('bidang_id') === (string) $bidang->id)>{{ $bidang->nama_bidang }}</option>
                @endforeach
            </select>
            <input type="number" name="tahun" value="{{ request('tahun', now()->year) }}" placeholder="Tahun" class="rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <select name="bulan" class="rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Semua bulan</option>
                @for ($month = 1; $month <= 12; $month++)
                    <option value="{{ $month }}" @selected((string) request('bulan') === (string) $month)>{{ $month }}</option>
                @endfor
            </select>
            <select name="status" class="rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Semua status</option>
                @foreach (['draft', 'aktif', 'selesai', 'diarsipkan'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kegiatan" class="rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <div class="xl:col-span-5 flex gap-3">
                <button type="submit" class="rounded-2xl bg-slate-900 px-5 py-3 font-semibold text-white">Terapkan filter</button>
                <a href="{{ route('rekap.index') }}" class="rounded-2xl border border-slate-300 px-5 py-3 font-semibold text-slate-700">Reset</a>
            </div>
        </form>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-slate-500">
                        <tr><th class="px-4 py-3 font-medium">Kegiatan</th><th class="px-4 py-3 font-medium">Bidang</th><th class="px-4 py-3 font-medium">Tanggal</th><th class="px-4 py-3 font-medium">Biaya</th><th class="px-4 py-3 font-medium">Aksi</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($kegiatans as $kegiatan)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-800">{{ $kegiatan->nama_kegiatan }}</p>
                                    <p class="text-slate-500">{{ ucfirst($kegiatan->status) }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $kegiatan->bidang?->nama_bidang ?: '-' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $kegiatan->tanggal_berangkat?->format('d M Y') }} - {{ $kegiatan->tanggal_pulang?->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-slate-600">Rp {{ number_format($kegiatan->total_biaya, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-3">
                                        @if (auth()->user()->isSuperAdmin() || auth()->user()->isAdminBidang())
                                            <a href="{{ route('kegiatan.show', $kegiatan) }}" class="text-emerald-600">Detail</a>
                                        @endif
                                        <a href="{{ route('rekap.kegiatan.excel', $kegiatan) }}" class="text-slate-900">Excel</a>
                                        <a href="{{ route('rekap.kegiatan.pdf', $kegiatan) }}" class="text-amber-600">PDF</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">Tidak ada data rekap.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $kegiatans->links() }}</div>
        </div>
    </div>
</x-app-layout>
