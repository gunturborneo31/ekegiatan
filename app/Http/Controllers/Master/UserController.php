<?php
namespace App\Http\Controllers\Master;
use App\Http\Controllers\Controller;
use App\Models\{Bidang, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function index() { return view('master.users.index', ['users' => User::with('bidang')->paginate(15)]); }
    public function create() { return view('master.users.create', ['bidangs' => Bidang::all()]); }
    public function store(Request $r) {
        $r->validate(['name'=>'required','email'=>'required|email|unique:users','password'=>'required|min:8','role'=>'required','bidang_id'=>'nullable|exists:bidang,id']);
        User::create(array_merge($r->only('name','nip','jabatan','email','role','bidang_id','phone','is_active'), ['password'=>Hash::make($r->password)]));
        return redirect()->route('master.users.index')->with('success','User berhasil ditambahkan.');
    }
    public function show(User $user) { return view('master.users.show', compact('user')); }
    public function edit(User $user) { return view('master.users.edit', ['user'=>$user,'bidangs'=>Bidang::all()]); }
    public function update(Request $r, User $user) {
        $r->validate(['name'=>'required','email'=>'required|email|unique:users,email,'.$user->id,'role'=>'required']);
        $data = $r->only('name','nip','jabatan','email','role','bidang_id','phone','is_active');
        if ($r->filled('password')) $data['password'] = Hash::make($r->password);
        $user->update($data);
        return redirect()->route('master.users.index')->with('success','User berhasil diupdate.');
    }
    public function destroy(User $user) { $user->delete(); return back()->with('success','User dihapus.'); }
}
