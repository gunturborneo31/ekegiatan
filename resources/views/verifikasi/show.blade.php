<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Modul verifikasi</p>
                <h2 class="text-2xl font-semibold text-slate-900">Detail laporan staff</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <form method="POST" action="{{ route('verifikasi.setujui', $kegiatanStaff) }}">
                    @csrf
                    <button type="submit" class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white">Setujui</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <dl class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div><dt class="text-sm text-slate-500">Staff</dt><dd class="mt-1 font-semibold text-slate-900">{{ $kegiatanStaff->user?->name }}</dd></div>
                <div><dt class="text-sm text-slate-500">Kegiatan</dt><dd class="mt-1 font-semibold text-slate-900">{{ $kegiatanStaff->kegiatan?->nama_kegiatan }}</dd></div>
                <div><dt class="text-sm text-slate-500">Status</dt><dd class="mt-1 text-slate-700">{{ $kegiatanStaff->status_label }}</dd></div>
                <div><dt class="text-sm text-slate-500">Verifikator</dt><dd class="mt-1 text-slate-700">{{ $kegiatanStaff->verifiedBy?->name ?: '-' }}</dd></div>
                <div class="md:col-span-2 xl:col-span-4"><dt class="text-sm text-slate-500">Kesimpulan</dt><dd class="mt-1 text-slate-700">{{ $kegiatanStaff->laporan?->kesimpulan ?: '-' }}</dd></div>
            </dl>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1fr_0.9fr]">
            <div class="space-y-6">
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Dokumentasi</h3>
                    <div class="mt-4 space-y-3">
                        @forelse ($kegiatanStaff->laporan?->dokumentasi ?? [] as $doc)
                            <div class="rounded-2xl border border-slate-100 p-4">
                                <p class="font-medium text-slate-800">{{ $doc->file_name }}</p>
                                <p class="text-sm text-slate-500">{{ $doc->keterangan ?: 'Tanpa keterangan' }}</p>
                                <a href="{{ route('storage.file', ['path' => $doc->file_path]) }}" class="mt-2 inline-block text-sm font-medium text-emerald-600">Lihat file</a>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Belum ada dokumentasi.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Biaya</h3>
                    <div class="mt-4 space-y-3">
                        @forelse ($kegiatanStaff->laporan?->biaya ?? [] as $biaya)
                            <div class="rounded-2xl border border-slate-100 p-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="font-medium text-slate-800">{{ ucfirst($biaya->jenis_biaya) }} • Rp {{ number_format($biaya->jumlah, 0, ',', '.') }}</p>
                                        <p class="text-sm text-slate-500">{{ $biaya->keterangan ?: 'Tanpa keterangan' }}</p>
                                    </div>
                                    @if ($biaya->file_bukti_path)
                                        <a href="{{ route('storage.file', ['path' => $biaya->file_bukti_path]) }}" class="text-sm font-medium text-emerald-600">Bukti</a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Belum ada biaya.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Ringkasan biaya</h3>
                    <div class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between rounded-2xl border border-slate-100 px-4 py-3"><span>Transport</span><span class="font-semibold">Rp {{ number_format($kegiatanStaff->laporan?->total_transport ?? 0, 0, ',', '.') }}</span></div>
                        <div class="flex items-center justify-between rounded-2xl border border-slate-100 px-4 py-3"><span>Makan</span><span class="font-semibold">Rp {{ number_format($kegiatanStaff->laporan?->total_makan ?? 0, 0, ',', '.') }}</span></div>
                        <div class="flex items-center justify-between rounded-2xl border border-slate-100 px-4 py-3"><span>Penginapan</span><span class="font-semibold">Rp {{ number_format($kegiatanStaff->laporan?->total_penginapan ?? 0, 0, ',', '.') }}</span></div>
                        <div class="flex items-center justify-between rounded-2xl bg-slate-900 px-4 py-3 text-white"><span>Total</span><span class="font-semibold">Rp {{ number_format($kegiatanStaff->laporan?->total_keseluruhan ?? 0, 0, ',', '.') }}</span></div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Kembalikan untuk revisi</h3>
                    <form method="POST" action="{{ route('verifikasi.revisi', $kegiatanStaff) }}" class="mt-4 space-y-4">
                        @csrf
                        <textarea name="catatan_revisi" rows="6" placeholder="Tuliskan catatan revisi" class="w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">{{ old('catatan_revisi', $kegiatanStaff->catatan_revisi) }}</textarea>
                        <button type="submit" class="rounded-2xl bg-amber-500 px-5 py-3 font-semibold text-white">Kirim revisi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
