<?php
namespace App\Http\Controllers\Laporan;
use App\Http\Controllers\Controller;
use App\Models\{KegiatanStaff, Laporan, LaporanDokumentasi, LaporanBiaya};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class LaporanController extends Controller
{
    private function authorizeLaporan(KegiatanStaff $ks): void
    {
        if (auth()->id() !== $ks->user_id) abort(403);
        if (in_array($ks->status_laporan, ['submitted','disetujui'])) abort(403, 'Laporan tidak dapat diedit.');
    }
    public function show(KegiatanStaff $kegiatanStaff)
    {
        if (auth()->id() !== $kegiatanStaff->user_id && !auth()->user()->isSuperAdmin()) abort(403);
        $laporan = $kegiatanStaff->laporan ?? Laporan::create(['kegiatan_staff_id'=>$kegiatanStaff->id]);
        $kegiatanStaff->load('kegiatan.bidang');
        $laporan->load('dokumentasi','biayaTransport','biayaMakan','biayaPenginapan');
        return view('laporan.form', compact('kegiatanStaff','laporan'));
    }
    public function simpan(Request $r, KegiatanStaff $kegiatanStaff)
    {
        $this->authorizeLaporan($kegiatanStaff);
        $laporan = $kegiatanStaff->laporan ?? Laporan::create(['kegiatan_staff_id'=>$kegiatanStaff->id]);
        $laporan->update(['kesimpulan'=>$r->kesimpulan]);
        if ($kegiatanStaff->status_laporan === 'belum') $kegiatanStaff->update(['status_laporan'=>'draf']);
        return back()->with('success','Draf tersimpan.');
    }
    public function submit(Request $r, KegiatanStaff $kegiatanStaff)
    {
        $this->authorizeLaporan($kegiatanStaff);
        $laporan = $kegiatanStaff->laporan;
        $r->validate(['kesimpulan'=>'required']);
        $laporan->update(['kesimpulan'=>$r->kesimpulan,'submitted_at'=>now()]);
        $kegiatanStaff->update(['status_laporan'=>'submitted']);
        return redirect()->route('tugas.index')->with('success','Laporan berhasil disubmit.');
    }
    public function uploadDokumentasi(Request $r, KegiatanStaff $kegiatanStaff)
    {
        $this->authorizeLaporan($kegiatanStaff);
        $r->validate(['file'=>'required|file|max:10240','keterangan'=>'nullable|string']);
        $laporan = $kegiatanStaff->laporan ?? Laporan::create(['kegiatan_staff_id'=>$kegiatanStaff->id]);
        $file = $r->file('file');
        $ext = $file->getClientOriginalExtension();
        $path = 'laporan/'.$laporan->id.'/dokumentasi/'.Str::uuid().'.'.$ext;
        Storage::disk('private')->put($path, file_get_contents($file));
        $urutan = $laporan->dokumentasi()->max('urutan') + 1;
        LaporanDokumentasi::create(['laporan_id'=>$laporan->id,'file_path'=>$path,'file_name'=>$file->getClientOriginalName(),'file_type'=>$ext,'file_size'=>$file->getSize(),'keterangan'=>$r->keterangan,'urutan'=>$urutan]);
        return back()->with('success','File berhasil diupload.');
    }
    public function hapusDokumentasi(LaporanDokumentasi $doc)
    {
        if (auth()->id() !== $doc->laporan->kegiatanStaff->user_id) abort(403);
        Storage::disk('private')->delete($doc->file_path);
        $doc->delete();
        return back()->with('success','File dihapus.');
    }
    public function tambahBiaya(Request $r, KegiatanStaff $kegiatanStaff)
    {
        $this->authorizeLaporan($kegiatanStaff);
        $r->validate(['jenis_biaya'=>'required|in:transport,makan,penginapan','jumlah'=>'required|numeric|min:0','file_bukti'=>'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240']);
        $laporan = $kegiatanStaff->laporan ?? Laporan::create(['kegiatan_staff_id'=>$kegiatanStaff->id]);
        $data = $r->only('jenis_biaya','jumlah','keterangan');
        $data['laporan_id'] = $laporan->id;
        if ($r->hasFile('file_bukti')) {
            $file = $r->file('file_bukti');
            $ext = $file->getClientOriginalExtension();
            $path = 'laporan/'.$laporan->id.'/biaya/'.Str::uuid().'.'.$ext;
            Storage::disk('private')->put($path, file_get_contents($file));
            $data['file_bukti_path'] = $path; $data['file_bukti_name'] = $file->getClientOriginalName(); $data['file_bukti_type'] = $ext;
        }
        LaporanBiaya::create($data);
        $laporan->recalculateTotals();
        return back()->with('success','Biaya ditambahkan.');
    }
    public function hapusBiaya(LaporanBiaya $biaya)
    {
        if (auth()->id() !== $biaya->laporan->kegiatanStaff->user_id) abort(403);
        if ($biaya->file_bukti_path) Storage::disk('private')->delete($biaya->file_bukti_path);
        $laporan = $biaya->laporan; $biaya->delete(); $laporan->recalculateTotals();
        return back()->with('success','Biaya dihapus.');
    }
}
