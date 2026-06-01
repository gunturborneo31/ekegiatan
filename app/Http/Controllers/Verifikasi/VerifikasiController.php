<?php
namespace App\Http\Controllers\Verifikasi;
use App\Http\Controllers\Controller;
use App\Models\KegiatanStaff;
use Illuminate\Http\Request;
class VerifikasiController extends Controller
{
    public function index(Request $r)
    {
        $user = $r->user();
        $list = KegiatanStaff::with(['user','kegiatan.bidang','laporan'])
            ->whereHas('kegiatan', fn($q) => $q->where('bidang_id', $user->bidang_id))
            ->where('status_laporan', 'submitted')->latest()->paginate(15);
        return view('verifikasi.index', compact('list'));
    }
    public function show(KegiatanStaff $kegiatanStaff)
    {
        $this->authorize_ks($kegiatanStaff);
        $kegiatanStaff->load('user','kegiatan.bidang','laporan.dokumentasi','laporan.biaya','verifiedBy');
        return view('verifikasi.show', compact('kegiatanStaff'));
    }
    public function setujui(Request $r, KegiatanStaff $kegiatanStaff)
    {
        $this->authorize_ks($kegiatanStaff);
        $kegiatanStaff->update(['status_laporan'=>'disetujui','verified_at'=>now(),'verified_by'=>$r->user()->id,'catatan_revisi'=>null]);
        return back()->with('success','Laporan telah disetujui.');
    }
    public function revisi(Request $r, KegiatanStaff $kegiatanStaff)
    {
        $this->authorize_ks($kegiatanStaff);
        $r->validate(['catatan_revisi'=>'required']);
        $kegiatanStaff->update(['status_laporan'=>'revisi','catatan_revisi'=>$r->catatan_revisi]);
        return back()->with('success','Laporan dikembalikan untuk revisi.');
    }
    private function authorize_ks(KegiatanStaff $ks): void
    {
        $user = auth()->user();
        if ($user->isAdminBidang() && $user->bidang_id === $ks->kegiatan->bidang_id) return;
        if ($user->isSuperAdmin()) return;
        abort(403);
    }
}
