<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Salary;
use App\Models\Employee;
use App\Models\VisitLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    // Form buat bikin akun baru + list semua akun yang ada
    public function index()
    {
        // Ambil semua data akun dari tabel user
        $users = User::filter(request(['search']))->paginate(30);

        // Tampilkan halaman account/index.blade.php dan kirimkan data semua akun ke blade-nya
        return view('accounts.index', compact('users'));
    }

    public function create(){
        return view('accounts.create', [
            "employees" => Employee::orderBy('nama', 'asc')->get()
        ]);
    }

    // Simpan data akun baru ke database
    public function store(Request $request)
    {
        // Validasi input data akun baru, kalo ada yang ga memenuhi ga bakal bisa lanjut
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            "role_id" => "required",
            'employee' => 'nullable'
        ]);

        // Kalo validasi lolos berarti langsung bikin dan tambahin datanya ke tabel users
        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        if($request->employee){
            Employee::find($request->employee)->update(['user_id' => $newUser]);
        }

        // Arahin user balik ke halaman account/index.blade.php
        return redirect()->route('account.index')->with("successCreateAccount", "Berhasil membuat akun baru.");
    }

    // Buat edit akun sekaligus tunjukin data akun
    public function show($id)
    {
        // Ambil data akun yang dipilih dari database
        $user = User::findOrFail($id);

        // Tampilkan halaman accounts/edit.blade.php dan kirim data akun tersebut ke blade-nya
        return view("accounts.edit", [
            "user" => $user,
            "employees" => Employee::orderBy('nama', 'asc')->get()
        ]);
    }

    // Simpan perubahan data akun ke database
    public function update(Request $request, $id)
    {
        // Targetkan data akun mana yang mau di-update berdasarkan yang dipilih di halaman sebelumnya
        $user = User::find($id);

        // Bikin aturan dasar validasinya (setidaknya nama sama email harus diisi pas form edit di-submit)
        $validationRule = [
            'name' => 'required|string|max:255',
            "email" => 'required|string|email|max:255',
            "role_id" => 'required',
            'employee' => 'nullable'
        ];

        // Aturan validasi opsional, berlaku jika input password dan konfirmasinya diisi
        if($request->password){
            $validationRule["password"] = 'string|min:8|confirmed';
        }

        // Validasi input
        $validatedData = $request->validate($validationRule);

        // Enkripsi password baru
        if($request->password){
            $validatedData["password"] = Hash::make($validatedData["password"]);
        }

        if($request->employee){
            Employee::find($request->employee)->update(['user_id' => $user->id]);
        }

        // Kalo semuanya udah baru disimpan perubahannya di tabel users
        $user->update($validatedData);

        // Arahin user balik ke halaman accounts/index.blade.php
        return redirect()->route('account.index')->with("successEditAccount", "Berhasil memperbaharui data akun.");
    }

    // Hapus akun
    public function destroy($id)
    {
        // Targetkan data akun yang mau dihapus sesuai dengan akun mana yang dipilih di halaman sebelumnya
        User::find($id)->delete();

        // Arahkan user kembali ke halaman accounts/index.blade.php
        return redirect()->route('account.index')->with("successDeleteAccount", "Berhasil menghapus akun.");
    }

    public function import_user_form(){
        return view("accounts.import-data");
    }

    public function edit_profile(){
        return view("pages.profile");
    }

    public function update_profile(Request $request){
        $validatedData = $request->validate([
            "name" => "required|min:3",
            "email" => "required|email:dns"
        ]);

        $email_changed = false;
        if(Auth::user()->email != $validatedData["email"]){
            $email_changed = true;
        }

        $user = User::find(Auth::user()->id);
        $user->update($validatedData);

        if($email_changed){
            $user->update(["email_verified_at" => null]);
        }

        return back()->with("successEditProfile", "Berhasil memperbaharui profil akun.");
    }

    public function visit_log(){
        return view("pages.visit-log.index", [
            "visit_logs" => VisitLog::latest()->paginate(30)
        ]);
    }
}
