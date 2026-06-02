<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Modul kegiatan</p>
            <h2 class="text-2xl font-semibold text-slate-900">Buat kegiatan baru</h2>
        </div>
    </x-slot>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <form method="POST" action="{{ route('kegiatan.store') }}" enctype="multipart/form-data">
            @include('kegiatan._form')
        </form>
    </div>
</x-app-layout>
