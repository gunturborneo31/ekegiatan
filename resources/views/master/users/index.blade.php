<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-slate-500">Master data</p>
                <h2 class="text-2xl font-semibold text-slate-900">Daftar user</h2>
            </div>
            <a href="{{ route('master.users.create') }}" class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white">Tambah user</a>
        </div>
    </x-slot>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-slate-500">
                    <tr><th class="px-4 py-3 font-medium">Nama</th><th class="px-4 py-3 font-medium">Role</th><th class="px-4 py-3 font-medium">Bidang</th><th class="px-4 py-3 font-medium">Status</th><th class="px-4 py-3 font-medium">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                <p class="text-slate-500">{{ $user->email }}</p>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ str_replace('_', ' ', ucfirst($user->role)) }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $user->bidang?->nama_bidang ?: '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('master.users.show', $user) }}" class="text-emerald-600">Detail</a>
                                    <a href="{{ route('master.users.edit', $user) }}" class="text-amber-600">Edit</a>
                                    <form method="POST" action="{{ route('master.users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">Belum ada user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $users->links() }}</div>
    </div>
</x-app-layout>
