<?php
namespace App\Http\Controllers\Master;
use App\Http\Controllers\Controller;
use App\Models\Bidang;
use Illuminate\Http\Request;
class BidangController extends Controller
{
    public function index() { return view('master.bidang.index', ['bidangs' => Bidang::paginate(15)]); }
    public function create() { return view('master.bidang.create'); }
    public function store(Request $r) {
        $r->validate(['nama_bidang'=>'required','kode_bidang'=>'required|unique:bidang']);
        Bidang::create($r->only('nama_bidang','kode_bidang','deskripsi'));
        return redirect()->route('master.bidang.index')->with('success','Bidang berhasil ditambahkan.');
    }
    public function edit(Bidang $bidang) { return view('master.bidang.edit', compact('bidang')); }
    public function update(Request $r, Bidang $bidang) {
        $r->validate(['nama_bidang'=>'required','kode_bidang'=>'required|unique:bidang,kode_bidang,'.$bidang->id]);
        $bidang->update($r->only('nama_bidang','kode_bidang','deskripsi'));
        return redirect()->route('master.bidang.index')->with('success','Bidang berhasil diupdate.');
    }
    public function destroy(Bidang $bidang) { $bidang->delete(); return back()->with('success','Bidang dihapus.'); }
    public function show(Bidang $bidang) { return view('master.bidang.show', compact('bidang')); }
}
