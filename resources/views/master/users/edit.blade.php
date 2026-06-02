<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Master data</p>
            <h2 class="text-2xl font-semibold text-slate-900">Edit user</h2>
        </div>
    </x-slot>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <form method="POST" action="{{ route('master.users.update', $user) }}">
            @method('PUT')
            @include('master.users._form')
        </form>
    </div>
</x-app-layout>
