@php
    $editable = auth()->id() === $kegiatanStaff->user_id && !in_array($kegiatanStaff->status_laporan, ['submitted', 'disetujui'], true);
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Modul laporan</p>
            <h2 class="text-2xl font-semibold text-slate-900">Form laporan kegiatan</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div><p class="text-sm text-slate-500">Kegiatan</p><p class="mt-1 font-semibold text-slate-900">{{ $kegiatanStaff->kegiatan?->nama_kegiatan }}</p></div>
                <div><p class="text-sm text-slate-500">Bidang</p><p class="mt-1 font-semibold text-slate-900">{{ $kegiatanStaff->kegiatan?->bidang?->nama_bidang }}</p></div>
                <div><p class="text-sm text-slate-500">Status</p><p class="mt-1 font-semibold text-slate-900">{{ $kegiatanStaff->status_label }}</p></div>
                <div><p class="text-sm text-slate-500">Periode</p><p class="mt-1 font-semibold text-slate-900">{{ $kegiatanStaff->kegiatan?->tanggal_berangkat?->format('d M Y') }} - {{ $kegiatanStaff->kegiatan?->tanggal_pulang?->format('d M Y') }}</p></div>
            </div>
            @if (!$editable)
                <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    Form tidak dapat diedit karena laporan sudah disubmit atau disetujui.
                </div>
            @endif
            @if ($kegiatanStaff->catatan_revisi)
                <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    Catatan revisi: {{ $kegiatanStaff->catatan_revisi }}
                </div>
            @endif
        </div>

        <div class="grid gap-6 xl:grid-cols-[1fr_0.9fr]">
            <div class="space-y-6">
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Kesimpulan laporan</h3>
                    <form method="POST" action="{{ route('tugas.laporan.simpan', $kegiatanStaff) }}" class="mt-4 space-y-4">
                        @csrf
                        <textarea name="kesimpulan" rows="8" class="w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" @disabled(!$editable)>{{ old('kesimpulan', $laporan->kesimpulan) }}</textarea>
                        <div class="flex flex-wrap gap-3">
                            <button type="submit" class="rounded-2xl bg-slate-900 px-5 py-3 font-semibold text-white disabled:cursor-not-allowed disabled:opacity-50" @disabled(!$editable)>Simpan draf</button>
                            <button type="submit" formaction="{{ route('tugas.laporan.submit', $kegiatanStaff) }}" class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white disabled:cursor-not-allowed disabled:opacity-50" @disabled(!$editable)>Submit laporan</button>
                        </div>
                    </form>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-lg font-semibold text-slate-900">Dokumentasi</h3>
                        <span class="text-sm text-slate-500">{{ $laporan->dokumentasi->count() }} file</span>
                    </div>
                    <form method="POST" action="{{ route('tugas.laporan.dokumentasi', $kegiatanStaff) }}" enctype="multipart/form-data" class="mt-4 grid gap-4 md:grid-cols-[1fr_1fr_auto]">
                        @csrf
                        <input type="file" name="file" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:font-semibold file:text-slate-700" @disabled(!$editable)>
                        <input type="text" name="keterangan" placeholder="Keterangan file" class="rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" @disabled(!$editable)>
                        <button type="submit" class="rounded-2xl bg-slate-900 px-5 py-3 font-semibold text-white disabled:cursor-not-allowed disabled:opacity-50" @disabled(!$editable)>Upload</button>
                    </form>
                    <div class="mt-4 space-y-3">
                        @forelse ($laporan->dokumentasi as $doc)
                            <div class="flex flex-col gap-3 rounded-2xl border border-slate-100 p-4 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="font-medium text-slate-800">{{ $doc->file_name }}</p>
                                    <p class="text-sm text-slate-500">{{ $doc->keterangan ?: 'Tanpa keterangan' }}</p>
                                </div>
                                <div class="flex gap-3">
                                    <a href="{{ route('storage.file', ['path' => $doc->file_path]) }}" class="text-sm font-medium text-emerald-600">Lihat</a>
                                    @if ($editable)
                                        <form method="POST" action="{{ route('tugas.laporan.hapus-dok', $doc) }}" onsubmit="return confirm('Hapus dokumentasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-rose-600">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Belum ada dokumentasi.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Tambah biaya</h3>
                    <form method="POST" action="{{ route('tugas.laporan.biaya', $kegiatanStaff) }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                        @csrf
                        <select name="jenis_biaya" class="w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" @disabled(!$editable)>
                            <option value="transport">Transport</option>
                            <option value="makan">Makan</option>
                            <option value="penginapan">Penginapan</option>
                        </select>
                        <input type="number" step="0.01" min="0" name="jumlah" placeholder="Jumlah" class="w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" @disabled(!$editable)>
                        <input type="text" name="keterangan" placeholder="Keterangan" class="w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" @disabled(!$editable)>
                        <input type="file" name="file_bukti" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:font-semibold file:text-slate-700" @disabled(!$editable)>
                        <button type="submit" class="rounded-2xl bg-slate-900 px-5 py-3 font-semibold text-white disabled:cursor-not-allowed disabled:opacity-50" @disabled(!$editable)>Tambah biaya</button>
                    </form>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Ringkasan biaya</h3>
                    <div class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between rounded-2xl border border-slate-100 px-4 py-3"><span>Transport</span><span class="font-semibold">Rp {{ number_format($laporan->total_transport, 0, ',', '.') }}</span></div>
                        <div class="flex items-center justify-between rounded-2xl border border-slate-100 px-4 py-3"><span>Makan</span><span class="font-semibold">Rp {{ number_format($laporan->total_makan, 0, ',', '.') }}</span></div>
                        <div class="flex items-center justify-between rounded-2xl border border-slate-100 px-4 py-3"><span>Penginapan</span><span class="font-semibold">Rp {{ number_format($laporan->total_penginapan, 0, ',', '.') }}</span></div>
                        <div class="flex items-center justify-between rounded-2xl bg-slate-900 px-4 py-3 text-white"><span>Total</span><span class="font-semibold">Rp {{ number_format($laporan->total_keseluruhan, 0, ',', '.') }}</span></div>
                    </div>
                    <div class="mt-6 space-y-4">
                        @foreach (['biayaTransport' => 'Transport', 'biayaMakan' => 'Makan', 'biayaPenginapan' => 'Penginapan'] as $relation => $label)
                            <div>
                                <p class="text-sm font-semibold text-slate-700">{{ $label }}</p>
                                <div class="mt-2 space-y-2">
                                    @forelse ($laporan->$relation as $biaya)
                                        <div class="rounded-2xl border border-slate-100 p-4">
                                            <div class="flex items-center justify-between gap-4">
                                                <div>
                                                    <p class="font-medium text-slate-800">Rp {{ number_format($biaya->jumlah, 0, ',', '.') }}</p>
                                                    <p class="text-sm text-slate-500">{{ $biaya->keterangan ?: 'Tanpa keterangan' }}</p>
                                                    @if ($biaya->file_bukti_path)
                                                        <a href="{{ route('storage.file', ['path' => $biaya->file_bukti_path]) }}" class="mt-1 inline-block text-sm font-medium text-emerald-600">Lihat bukti</a>
                                                    @endif
                                                </div>
                                                @if ($editable)
                                                    <form method="POST" action="{{ route('tugas.laporan.hapus-biaya', $biaya) }}" onsubmit="return confirm('Hapus biaya ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-sm font-medium text-rose-600">Hapus</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-slate-500">Belum ada biaya {{ strtolower($label) }}.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
