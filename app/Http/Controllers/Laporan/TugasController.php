<?php
namespace App\Http\Controllers\Laporan;
use App\Http\Controllers\Controller;
use App\Models\KegiatanStaff;
use Illuminate\Http\Request;
class TugasController extends Controller
{
    public function index(Request $r)
    {
        $user = $r->user();
        $list = KegiatanStaff::with(['kegiatan.bidang'])
            ->where('user_id', $user->id)->latest()->paginate(15);
        return view('tugas.index', compact('list'));
    }
    public function show(KegiatanStaff $kegiatanStaff)
    {
        if (auth()->id() !== $kegiatanStaff->user_id && !auth()->user()->isSuperAdmin()) abort(403);
        $kegiatanStaff->load('kegiatan.bidang','laporan');
        return view('tugas.show', compact('kegiatanStaff'));
    }
}
