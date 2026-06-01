<?php
namespace App\Http\Controllers\Kegiatan;
use App\Http\Controllers\Controller;
use App\Models\{Kegiatan, User, KegiatanStaff};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class KegiatanController extends Controller
{
    public function index(Request $r)
    {
        $user = $r->user();
        $q = Kegiatan::with('bidang','pembuat');
        if ($user->isAdminBidang()) $q->where('bidang_id', $user->bidang_id);
        if ($r->search) $q->where('nama_kegiatan','like',"%{$r->search}%");
        if ($r->status) $q->where('status', $r->status);
        return view('kegiatan.index', ['kegiatans' => $q->latest()->paginate(15)]);
    }
    public function create(Request $r)
    {
        $user = $r->user();
        $staffList = User::active()->byBidang($user->bidang_id)->whereIn('role',['staff','admin_bidang','pimpinan'])->get();
        return view('kegiatan.create', compact('staffList'));
    }
    public function store(Request $r)
    {
        $r->validate([
            'nama_kegiatan'=>'required','tanggal_berangkat'=>'required|date',
            'tanggal_pulang'=>'required|date|after_or_equal:tanggal_berangkat',
            'lokasi'=>'required','file_surat'=>'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'staff_ids'=>'nullable|array','staff_ids.*'=>'exists:users,id',
        ]);
        $user = $r->user();
        $data = $r->only('nama_kegiatan','deskripsi','tanggal_berangkat','tanggal_pulang','lokasi','nomor_surat','status');
        $data['bidang_id'] = $user->bidang_id;
        $data['created_by'] = $user->id;
        if ($r->hasFile('file_surat')) {
            $file = $r->file('file_surat');
            $path = 'surat_kegiatan/tmp/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            Storage::disk('private')->put($path, file_get_contents($file));
            $data['file_surat'] = $path; $data['file_surat_name'] = $file->getClientOriginalName();
        }
        $kegiatan = Kegiatan::create($data);
        // Move file to proper path
        if (isset($data['file_surat'])) {
            $newPath = 'surat_kegiatan/'.$kegiatan->id.'/'.basename($data['file_surat']);
            Storage::disk('private')->move($data['file_surat'], $newPath);
            $kegiatan->update(['file_surat' => $newPath]);
        }
        foreach (($r->staff_ids ?? []) as $staffId) {
            KegiatanStaff::create(['kegiatan_id'=>$kegiatan->id,'user_id'=>$staffId]);
        }
        return redirect()->route('kegiatan.show',$kegiatan)->with('success','Kegiatan berhasil dibuat.');
    }
    public function show(Kegiatan $kegiatan)
    {
        $this->authorizeKegiatan($kegiatan);
        $kegiatan->load('bidang','pembuat','kegiatanStaff.user','kegiatanStaff.laporan');
        return view('kegiatan.show', compact('kegiatan'));
    }
    public function edit(Kegiatan $kegiatan)
    {
        $this->authorizeKegiatan($kegiatan);
        $user = auth()->user();
        $staffList = User::active()->byBidang($user->bidang_id)->whereIn('role',['staff','admin_bidang','pimpinan'])->get();
        return view('kegiatan.edit', compact('kegiatan','staffList'));
    }
    public function update(Request $r, Kegiatan $kegiatan)
    {
        $this->authorizeKegiatan($kegiatan);
        $r->validate([
            'nama_kegiatan'=>'required','tanggal_berangkat'=>'required|date',
            'tanggal_pulang'=>'required|date|after_or_equal:tanggal_berangkat',
            'lokasi'=>'required','file_surat'=>'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);
        $data = $r->only('nama_kegiatan','deskripsi','tanggal_berangkat','tanggal_pulang','lokasi','nomor_surat','status');
        if ($r->hasFile('file_surat')) {
            if ($kegiatan->file_surat) Storage::disk('private')->delete($kegiatan->file_surat);
            $file = $r->file('file_surat');
            $path = 'surat_kegiatan/'.$kegiatan->id.'/'.Str::uuid().'.'.$file->getClientOriginalExtension();
            Storage::disk('private')->put($path, file_get_contents($file));
            $data['file_surat'] = $path; $data['file_surat_name'] = $file->getClientOriginalName();
        }
        $kegiatan->update($data);
        // Sync staff
        $existing = $kegiatan->kegiatanStaff()->pluck('user_id')->toArray();
        $newIds = $r->staff_ids ?? [];
        foreach (array_diff($newIds, $existing) as $id) KegiatanStaff::create(['kegiatan_id'=>$kegiatan->id,'user_id'=>$id]);
        foreach (array_diff($existing, $newIds) as $id) $kegiatan->kegiatanStaff()->where('user_id',$id)->delete();
        return redirect()->route('kegiatan.show',$kegiatan)->with('success','Kegiatan berhasil diupdate.');
    }
    public function destroy(Kegiatan $kegiatan)
    {
        $this->authorizeKegiatan($kegiatan);
        $kegiatan->delete();
        return redirect()->route('kegiatan.index')->with('success','Kegiatan dihapus.');
    }
    private function authorizeKegiatan(Kegiatan $kegiatan): void
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) return;
        if ($user->isAdminBidang() && $user->bidang_id === $kegiatan->bidang_id) return;
        if ($user->isPimpinan()) return;
        abort(403);
    }
}
