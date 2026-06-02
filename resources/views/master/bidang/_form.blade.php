@csrf
<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-700">Nama bidang</label>
        <input type="text" name="nama_bidang" value="{{ old('nama_bidang', $bidang->nama_bidang ?? '') }}" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Kode bidang</label>
        <input type="text" name="kode_bidang" value="{{ old('kode_bidang', $bidang->kode_bidang ?? '') }}" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
    </div>
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-700">Deskripsi</label>
        <textarea name="deskripsi" rows="4" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">{{ old('deskripsi', $bidang->deskripsi ?? '') }}</textarea>
    </div>
</div>
<div class="mt-6 flex gap-3">
    <button type="submit" class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white">Simpan</button>
    <a href="{{ route('master.bidang.index') }}" class="rounded-2xl border border-slate-300 px-5 py-3 font-semibold text-slate-700">Kembali</a>
</div>
