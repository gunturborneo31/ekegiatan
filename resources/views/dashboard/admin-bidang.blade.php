<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Dashboard</p>
            <h2 class="text-2xl font-semibold text-slate-900">Ringkasan admin bidang</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Total kegiatan</p><p class="mt-3 text-3xl font-semibold">{{ $totalKegiatan }}</p></div>
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Pending verifikasi</p><p class="mt-3 text-3xl font-semibold">{{ $pendingVerifikasi }}</p></div>
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Total disetujui</p><p class="mt-3 text-3xl font-semibold">{{ $totalDisetujui }}</p></div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Laporan perlu verifikasi</h3>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Staff</th>
                            <th class="px-4 py-3 font-medium">Kegiatan</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($laporanPending as $item)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $item->user?->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $item->kegiatan?->nama_kegiatan }}</td>
                                <td class="px-4 py-3"><a href="{{ route('verifikasi.show', $item) }}" class="font-medium text-emerald-600">Lihat detail</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-6 text-center text-slate-500">Belum ada laporan yang menunggu verifikasi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
