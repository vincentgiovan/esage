<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Form login
    public function index()
    {
        // Tampilkan halaman accouns/login.blade.php
        return view('accounts.login');
    }

    // Proses authentication
    public function checkLogin(Request $request)
    {
        // Validasi data, kalo ga sesuai ga bisa lanjut
        $validatedData = $request->validate([
            "email" => "required|email:dns",
            "password" => "required|min:4|max:16"
        ]);

        // Main logic buat ngecek kredensial (pake fitur authentication punyanya Laravel)
        $valid = Auth::attempt($validatedData);

        // Kalau kredensial sesuai:
        if($valid){
            $request->session()->regenerate(); // Simpan data login user
            return redirect()->intended(route("dashboard"))->with("successfulLogin", "Welcome back, " . Auth::user()->name . "!");; // Arahkan user ke halaman dashboard
        }

        // Kalo ga ada yang match sama sekali tampilkan pesan gagal login
        return redirect(route("login"))->with("failMessage", "Login failed!");
    }

    // Logout
    public function logout(Request $request)
    {
        // Logout dan bersihkan data login user
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan user kembali ke halaman login
        return redirect(route("login"));

    }

    // ?? kayaknya ga kepake
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password', 'remember');
    }

}
