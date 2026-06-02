@php
    $selectedStaffIds = collect(old('staff_ids', isset($kegiatan) ? $kegiatan->kegiatanStaff->pluck('user_id')->all() : []))
        ->map(fn ($id) => (int) $id)
        ->all();
@endphp

@csrf
<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-700">Nama kegiatan</label>
        <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan ?? '') }}" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Tanggal berangkat</label>
        <input type="date" name="tanggal_berangkat" value="{{ old('tanggal_berangkat', isset($kegiatan) && $kegiatan->tanggal_berangkat ? $kegiatan->tanggal_berangkat->format('Y-m-d') : '') }}" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Tanggal pulang</label>
        <input type="date" name="tanggal_pulang" value="{{ old('tanggal_pulang', isset($kegiatan) && $kegiatan->tanggal_pulang ? $kegiatan->tanggal_pulang->format('Y-m-d') : '') }}" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Lokasi</label>
        <input type="text" name="lokasi" value="{{ old('lokasi', $kegiatan->lokasi ?? '') }}" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Nomor surat</label>
        <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $kegiatan->nomor_surat ?? '') }}" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Status</label>
        <select name="status" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            @foreach (['draft' => 'Draft', 'aktif' => 'Aktif', 'selesai' => 'Selesai', 'diarsipkan' => 'Diarsipkan'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $kegiatan->status ?? 'draft') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-700">Deskripsi</label>
        <textarea name="deskripsi" rows="4" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">{{ old('deskripsi', $kegiatan->deskripsi ?? '') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-700">File surat</label>
        <input type="file" name="file_surat" class="mt-2 block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:font-semibold file:text-slate-700">
        @if (!empty($kegiatan?->file_surat))
            <p class="mt-2 text-sm text-slate-500">File saat ini: <a href="{{ route('storage.file', ['path' => $kegiatan->file_surat]) }}" class="font-medium text-emerald-600">{{ $kegiatan->file_surat_name ?: 'Lihat file' }}</a></p>
        @endif
    </div>
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-700">Staff yang ditugaskan</label>
        <div class="mt-3 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($staffList as $staff)
                <label class="flex items-start gap-3 rounded-2xl border border-slate-200 px-4 py-3">
                    <input type="checkbox" name="staff_ids[]" value="{{ $staff->id }}" class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" @checked(in_array($staff->id, $selectedStaffIds, true))>
                    <span>
                        <span class="block font-medium text-slate-800">{{ $staff->name }}</span>
                        <span class="text-sm text-slate-500">{{ $staff->jabatan ?: str_replace('_', ' ', ucfirst($staff->role)) }}</span>
                    </span>
                </label>
            @empty
                <p class="text-sm text-slate-500">Belum ada staff aktif dalam bidang ini.</p>
            @endforelse
        </div>
    </div>
</div>
<div class="mt-6 flex gap-3">
    <button type="submit" class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white">Simpan</button>
    <a href="{{ route('kegiatan.index') }}" class="rounded-2xl border border-slate-300 px-5 py-3 font-semibold text-slate-700">Kembali</a>
</div>
