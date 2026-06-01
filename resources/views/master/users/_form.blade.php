@csrf
<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label class="text-sm font-medium text-slate-700">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">NIP</label>
        <input type="text" name="nip" value="{{ old('nip', $user->nip ?? '') }}" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Jabatan</label>
        <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan ?? '') }}" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">No. HP</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Password {{ isset($user) ? '(opsional)' : '' }}</label>
        <input type="password" name="password" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Role</label>
        <select name="role" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            @foreach (['super_admin' => 'Super Admin', 'pimpinan' => 'Pimpinan', 'admin_bidang' => 'Admin Bidang', 'staff' => 'Staff'] as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $user->role ?? 'staff') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Bidang</label>
        <select name="bidang_id" class="mt-2 w-full rounded-2xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Tanpa bidang</option>
            @foreach ($bidangs as $bidang)
                <option value="{{ $bidang->id }}" @selected((string) old('bidang_id', $user->bidang_id ?? '') === (string) $bidang->id)>{{ $bidang->nama_bidang }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <input type="hidden" name="is_active" value="0">
        <label class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700">
            <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" @checked((bool) old('is_active', $user->is_active ?? true))>
            User aktif
        </label>
    </div>
</div>
<div class="mt-6 flex gap-3">
    <button type="submit" class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white">Simpan</button>
    <a href="{{ route('master.users.index') }}" class="rounded-2xl border border-slate-300 px-5 py-3 font-semibold text-slate-700">Kembali</a>
</div>
