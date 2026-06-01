<?php
namespace App\Http\Controllers\Rekap;
use App\Http\Controllers\Controller;
use App\Models\{Bidang, Kegiatan};
use Illuminate\Http\Request;
class RekapController extends Controller
{
    public function index(Request $r)
    {
        $user = $r->user();
        $q = Kegiatan::with('bidang','kegiatanStaff.laporan');
        if ($user->isAdminBidang()) $q->where('bidang_id', $user->bidang_id);
        if ($r->bidang_id) $q->where('bidang_id', $r->bidang_id);
        if ($r->tahun) $q->whereYear('tanggal_berangkat', $r->tahun);
        else $q->whereYear('tanggal_berangkat', now()->year);
        if ($r->bulan) $q->whereMonth('tanggal_berangkat', $r->bulan);
        if ($r->status) $q->where('status', $r->status);
        if ($r->search) $q->where('nama_kegiatan','like',"%{$r->search}%");
        $kegiatans = $q->latest()->paginate(15)->withQueryString();
        $bidangs = $user->isSuperAdmin() || $user->isPimpinan() ? Bidang::all() : collect([$user->bidang]);
        return view('rekap.index', compact('kegiatans','bidangs'));
    }
    public function exportExcel(Request $r)
    {
        $filters = $r->only('bidang_id','tahun','bulan','status','search');
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\RekapExport($filters, auth()->user()), 'rekap-kegiatan.xlsx');
    }
    public function exportPdf(Request $r)
    {
        $filters = $r->only('bidang_id','tahun','bulan','status','search');
        $user = auth()->user();
        $q = Kegiatan::with('bidang','kegiatanStaff.user','kegiatanStaff.laporan');
        if ($user->isAdminBidang()) $q->where('bidang_id', $user->bidang_id);
        if ($filters['bidang_id'] ?? null) $q->where('bidang_id', $filters['bidang_id']);
        if ($filters['tahun'] ?? null) $q->whereYear('tanggal_berangkat', $filters['tahun']);
        $kegiatans = $q->latest()->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('rekap.pdf', compact('kegiatans'));
        return $pdf->download('rekap-kegiatan.pdf');
    }
    public function exportKegiatanExcel(Kegiatan $kegiatan)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\KegiatanExport($kegiatan), 'kegiatan-'.$kegiatan->id.'.xlsx');
    }
    public function exportKegiatanPdf(Kegiatan $kegiatan)
    {
        $kegiatan->load('bidang','pembuat','kegiatanStaff.user','kegiatanStaff.laporan.biaya');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('rekap.kegiatan-pdf', compact('kegiatan'));
        return $pdf->download('kegiatan-'.$kegiatan->id.'.pdf');
    }
}
