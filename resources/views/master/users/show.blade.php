<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-slate-500">Master data</p>
                <h2 class="text-2xl font-semibold text-slate-900">Detail user</h2>
            </div>
            <a href="{{ route('master.users.edit', $user) }}" class="rounded-2xl border border-slate-300 px-5 py-3 font-semibold text-slate-700">Edit</a>
        </div>
    </x-slot>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <dl class="grid gap-6 md:grid-cols-2">
            <div><dt class="text-sm text-slate-500">Nama</dt><dd class="mt-1 text-lg font-semibold text-slate-900">{{ $user->name }}</dd></div>
            <div><dt class="text-sm text-slate-500">Email</dt><dd class="mt-1 text-slate-700">{{ $user->email }}</dd></div>
            <div><dt class="text-sm text-slate-500">Role</dt><dd class="mt-1 text-slate-700">{{ str_replace('_', ' ', ucfirst($user->role)) }}</dd></div>
            <div><dt class="text-sm text-slate-500">Bidang</dt><dd class="mt-1 text-slate-700">{{ $user->bidang?->nama_bidang ?: '-' }}</dd></div>
            <div><dt class="text-sm text-slate-500">NIP</dt><dd class="mt-1 text-slate-700">{{ $user->nip ?: '-' }}</dd></div>
            <div><dt class="text-sm text-slate-500">Jabatan</dt><dd class="mt-1 text-slate-700">{{ $user->jabatan ?: '-' }}</dd></div>
            <div><dt class="text-sm text-slate-500">Telepon</dt><dd class="mt-1 text-slate-700">{{ $user->phone ?: '-' }}</dd></div>
            <div><dt class="text-sm text-slate-500">Status</dt><dd class="mt-1 text-slate-700">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</dd></div>
        </dl>
    </div>
</x-app-layout>
