<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Modul kegiatan</p>
                <h2 class="text-2xl font-semibold text-slate-900">Daftar kegiatan</h2>
            </div>
            <a href="{{ route('kegiatan.create') }}" class="rounded-2xl bg-emerald-600 px-5 py-3 text-center font-semibold text-white">Buat kegiatan</a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <form method="GET" class="grid gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 md:grid-cols-[1fr_200px_auto]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kegiatan" class="rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <select name="status" class="rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Semua status</option>
                @foreach (['draft', 'aktif', 'selesai', 'diarsipkan'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-2xl bg-slate-900 px-5 py-3 font-semibold text-white">Filter</button>
        </form>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-slate-500">
                        <tr><th class="px-4 py-3 font-medium">Kegiatan</th><th class="px-4 py-3 font-medium">Bidang</th><th class="px-4 py-3 font-medium">Tanggal</th><th class="px-4 py-3 font-medium">Status</th><th class="px-4 py-3 font-medium">Aksi</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($kegiatans as $kegiatan)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-800">{{ $kegiatan->nama_kegiatan }}</p>
                                    <p class="text-slate-500">{{ $kegiatan->lokasi }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $kegiatan->bidang?->nama_bidang ?: '-' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $kegiatan->tanggal_berangkat?->format('d M Y') }} - {{ $kegiatan->tanggal_pulang?->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ ucfirst($kegiatan->status) }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-3">
                                        <a href="{{ route('kegiatan.show', $kegiatan) }}" class="text-emerald-600">Detail</a>
                                        <a href="{{ route('kegiatan.edit', $kegiatan) }}" class="text-amber-600">Edit</a>
                                        <form method="POST" action="{{ route('kegiatan.destroy', $kegiatan) }}" onsubmit="return confirm('Hapus kegiatan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">Belum ada kegiatan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $kegiatans->links() }}</div>
        </div>
    </div>
</x-app-layout>
