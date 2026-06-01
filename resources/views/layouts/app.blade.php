<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'e-Kegiatan') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-100 font-sans text-slate-900 antialiased">
        @php
            $user = auth()->user();
            $navItems = array_values(array_filter([
                ['label' => 'Dashboard', 'route' => route('dashboard'), 'active' => request()->routeIs('dashboard'), 'show' => true],
                ['label' => 'Bidang', 'route' => route('master.bidang.index'), 'active' => request()->routeIs('master.bidang.*'), 'show' => $user?->isSuperAdmin()],
                ['label' => 'Users', 'route' => route('master.users.index'), 'active' => request()->routeIs('master.users.*'), 'show' => $user?->isSuperAdmin()],
                ['label' => 'Kegiatan', 'route' => route('kegiatan.index'), 'active' => request()->routeIs('kegiatan.*'), 'show' => $user && ($user->isSuperAdmin() || $user->isAdminBidang())],
                ['label' => 'Verifikasi', 'route' => route('verifikasi.index'), 'active' => request()->routeIs('verifikasi.*'), 'show' => $user?->isAdminBidang()],
                ['label' => 'Tugas', 'route' => route('tugas.index'), 'active' => request()->routeIs('tugas.*'), 'show' => $user && ($user->isStaff() || $user->isAdminBidang() || $user->isPimpinan())],
                ['label' => 'Rekap', 'route' => route('rekap.index'), 'active' => request()->routeIs('rekap.*'), 'show' => (bool) $user],
                ['label' => 'Profile', 'route' => route('profile.edit'), 'active' => request()->routeIs('profile.*'), 'show' => (bool) $user],
            ], fn ($item) => $item['show']));
        @endphp

        <div class="min-h-screen lg:flex">
            <aside class="hidden w-72 shrink-0 flex-col border-r border-slate-200 bg-slate-900 text-white lg:flex">
                <div class="border-b border-white/10 px-6 py-6">
                    <div class="flex items-center gap-4">
                        <x-application-logo class="h-14 w-14 text-emerald-400" />
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-300">Mahakam Ulu</p>
                            <h1 class="text-xl font-semibold">e-Kegiatan</h1>
                            <p class="text-sm text-slate-300">Branding sidebar placeholder</p>
                        </div>
                    </div>
                </div>

                <nav class="flex-1 space-y-1 px-4 py-6">
                    @foreach ($navItems as $item)
                        <a href="{{ $item['route'] }}"
                           class="block rounded-xl px-4 py-3 text-sm font-medium transition {{ $item['active'] ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-950/30' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="border-t border-white/10 px-6 py-5 text-sm text-slate-300">
                    <p class="font-semibold text-white">{{ $user?->name }}</p>
                    <p>{{ $user?->role ? ucwords(str_replace('_', ' ', $user->role)) : '' }}</p>
                    @if ($user?->bidang)
                        <p>{{ $user->bidang->nama_bidang }}</p>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="inline-flex rounded-lg bg-white/10 px-4 py-2 font-medium text-white transition hover:bg-white/20">
                            Keluar
                        </button>
                    </form>
                </div>
            </aside>

            <div class="flex min-h-screen flex-1 flex-col">
                <div class="border-b border-slate-200 bg-white px-4 py-4 shadow-sm lg:hidden">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <x-application-logo class="h-12 w-12 text-emerald-500" />
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Mahakam Ulu</p>
                                <p class="text-lg font-semibold text-slate-900">e-Kegiatan</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700">
                                Keluar
                            </button>
                        </form>
                    </div>
                    <div class="mt-4 flex gap-2 overflow-x-auto pb-1">
                        @foreach ($navItems as $item)
                            <a href="{{ $item['route'] }}"
                               class="whitespace-nowrap rounded-full px-4 py-2 text-sm font-medium {{ $item['active'] ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-700' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                @if (isset($header))
                    <header class="border-b border-slate-200 bg-white/90 px-4 py-6 shadow-sm backdrop-blur sm:px-6 lg:px-8">
                        {{ $header }}
                    </header>
                @endif

                <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            <p class="font-semibold">Periksa input berikut:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
