<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AccountCreationController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('accounts.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            "role" => "required"
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('account.index');
    }

    public function show($id){
        $user = User::findOrFail($id);

        return view("accounts.show", ["user" => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::where("id", $id);

        $validationRule = [
            'name' => 'required|string|max:255',
            "email" => 'required|string|email|max:255'
        ];

        if($request->password){
            $validationRule["password"] = 'string|min:8|confirmed';
        }

        $validatedData = $request->validate($validationRule);

        $validatedData["password"] = Hash::make($validatedData["password"]);

        $user->update($validatedData);

        return redirect()->route('account.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('account.index');
    }
}
