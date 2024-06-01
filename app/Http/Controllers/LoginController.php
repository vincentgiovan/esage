<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        //user mau login
        return view('accounts.login');
    }
    public function checkLogin(Request $request){
        //Validasi data
        $validatedData = $request->validate([
            "email" => "required|email:dns",
            "password" => "required|min:4|max:16"
        ]);


        //Main logic buat ngecek kredensial (pake fitur authentication punyanya Laravel)
        $valid = Auth::attempt($validatedData);
        if($valid){
            $request->session()->regenerate();
            return redirect()->intended(route("dashboard"));
        }

        //Kalo ga ada yang match sama sekali
        return redirect(route("login"))->with("failMessage", "Login failed!");
    }
    public function logout(Request $request){
        //Logout dan clear data
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        //Balikin ke halaman login
        return redirect(route("login"));

    }
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password', 'remember');
    }

}
