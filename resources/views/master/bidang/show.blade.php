<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-slate-500">Master data</p>
                <h2 class="text-2xl font-semibold text-slate-900">Detail bidang</h2>
            </div>
            <a href="{{ route('master.bidang.edit', $bidang) }}" class="rounded-2xl border border-slate-300 px-5 py-3 font-semibold text-slate-700">Edit</a>
        </div>
    </x-slot>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <dl class="grid gap-6 md:grid-cols-2">
            <div><dt class="text-sm text-slate-500">Nama bidang</dt><dd class="mt-1 text-lg font-semibold text-slate-900">{{ $bidang->nama_bidang }}</dd></div>
            <div><dt class="text-sm text-slate-500">Kode bidang</dt><dd class="mt-1 text-lg font-semibold text-slate-900">{{ $bidang->kode_bidang }}</dd></div>
            <div class="md:col-span-2"><dt class="text-sm text-slate-500">Deskripsi</dt><dd class="mt-1 text-slate-700">{{ $bidang->deskripsi ?: '-' }}</dd></div>
        </dl>
    </div>
</x-app-layout>
