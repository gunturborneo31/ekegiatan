<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Kegiatan;
use App\Models\KegiatanStaff;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return match($user->role) {
            'super_admin' => $this->superAdminDashboard(),
            'pimpinan'    => $this->pimpinanDashboard(),
            'admin_bidang'=> $this->adminBidangDashboard($user),
            'staff'       => $this->staffDashboard($user),
            default       => abort(403),
        };
    }

    private function superAdminDashboard()
    {
        $totalUser   = User::where('role', '!=', 'super_admin')->count();
        $totalBidang = Bidang::count();
        $totalKegiatan = Kegiatan::whereMonth('created_at', now()->month)->count();

        $kegiatanPerBulan = Kegiatan::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as total')
        )->whereYear('created_at', now()->year)
         ->groupBy('bulan')->orderBy('bulan')->get();

        $recentKegiatan = Kegiatan::with('bidang', 'pembuat')->latest()->take(5)->get();

        return view('dashboard.super-admin', compact(
            'totalUser', 'totalBidang', 'totalKegiatan', 'kegiatanPerBulan', 'recentKegiatan'
        ));
    }

    private function pimpinanDashboard()
    {
        $totalAktif = Kegiatan::where('status', 'aktif')->count();
        $totalDisetujui = KegiatanStaff::where('status_laporan', 'disetujui')
            ->whereMonth('verified_at', now()->month)->count();
        $totalPending = KegiatanStaff::where('status_laporan', 'submitted')->count();

        $biayaPerBidang = Bidang::with(['kegiatan.kegiatanStaff.laporan'])->get()->map(function ($bidang) {
            $total = $bidang->kegiatan->flatMap(fn($k) => $k->kegiatanStaff)
                ->flatMap(fn($ks) => $ks->laporan ? [$ks->laporan] : [])
                ->sum('total_keseluruhan');
            return ['nama' => $bidang->nama_bidang, 'total' => $total];
        });

        $recentKegiatan = Kegiatan::with('bidang')->latest()->take(10)->get();

        return view('dashboard.pimpinan', compact(
            'totalAktif', 'totalDisetujui', 'totalPending', 'biayaPerBidang', 'recentKegiatan'
        ));
    }

    private function adminBidangDashboard($user)
    {
        $totalKegiatan = Kegiatan::where('bidang_id', $user->bidang_id)->count();
        $pendingVerifikasi = KegiatanStaff::whereHas('kegiatan', fn($q) => $q->where('bidang_id', $user->bidang_id))
            ->where('status_laporan', 'submitted')->count();
        $totalDisetujui = KegiatanStaff::whereHas('kegiatan', fn($q) => $q->where('bidang_id', $user->bidang_id))
            ->where('status_laporan', 'disetujui')->count();

        $laporanPending = KegiatanStaff::with(['user', 'kegiatan'])
            ->whereHas('kegiatan', fn($q) => $q->where('bidang_id', $user->bidang_id))
            ->where('status_laporan', 'submitted')
            ->latest()->take(10)->get();

        return view('dashboard.admin-bidang', compact(
            'totalKegiatan', 'pendingVerifikasi', 'totalDisetujui', 'laporanPending'
        ));
    }

    private function staffDashboard($user)
    {
        $tugasAktif = KegiatanStaff::with(['kegiatan.bidang'])
            ->where('user_id', $user->id)
            ->whereHas('kegiatan', fn($q) => $q->whereIn('status', ['aktif', 'selesai']))
            ->where('status_laporan', '!=', 'disetujui')
            ->latest()->get();

        $riwayat = KegiatanStaff::with(['kegiatan.bidang'])
            ->where('user_id', $user->id)
            ->where('status_laporan', 'disetujui')
            ->latest()->take(5)->get();

        return view('dashboard.staff', compact('tugasAktif', 'riwayat'));
    }
}
