<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-slate-500">Master data</p>
                <h2 class="text-2xl font-semibold text-slate-900">Daftar bidang</h2>
            </div>
            <a href="{{ route('master.bidang.create') }}" class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white">Tambah bidang</a>
        </div>
    </x-slot>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-slate-500">
                    <tr><th class="px-4 py-3 font-medium">Nama</th><th class="px-4 py-3 font-medium">Kode</th><th class="px-4 py-3 font-medium">Deskripsi</th><th class="px-4 py-3 font-medium">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($bidangs as $bidang)
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $bidang->nama_bidang }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $bidang->kode_bidang }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $bidang->deskripsi ?: '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('master.bidang.show', $bidang) }}" class="text-emerald-600">Detail</a>
                                    <a href="{{ route('master.bidang.edit', $bidang) }}" class="text-amber-600">Edit</a>
                                    <form method="POST" action="{{ route('master.bidang.destroy', $bidang) }}" onsubmit="return confirm('Hapus bidang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-slate-500">Belum ada bidang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $bidangs->links() }}</div>
    </div>
</x-app-layout>
