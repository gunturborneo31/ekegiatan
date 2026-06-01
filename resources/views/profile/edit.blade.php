<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Akun</p>
            <h2 class="text-2xl font-semibold text-slate-900">Profil pengguna</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="max-w-xl">
                <livewire:profile.update-profile-information-form />
            </div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="max-w-xl">
                <livewire:profile.update-password-form />
            </div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="max-w-xl">
                <livewire:profile.delete-user-form />
            </div>
        </div>
    </div>
</x-app-layout>
