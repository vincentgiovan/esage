<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class AccountController extends Controller
{
    public function index(){
        return view("pages.deliveryorder", [
            "test" => "test"
        ]);
    }

    public function create(){
        return view("inputitem");
    }


    public function store(Request $request){
        $validatedData = $request->validate([
            "product_name" => "required|min:3",
            "price"=>"required|numeric|min:0|not_in:0",
            "variant" => "required|min:3",
            "stock" => "required|numeric|min:0|not_in:0",
            "unit"=>"required"
        ]);


        $user = User::where("name", session("logged_in_user"))->first();
        $validatedData["user_id"] = $user->id;

        User::create($validatedData);
        return redirect("/dashboard")->with("successAddProduct", "Product added successfully!");


    }
    public function edit($id){


        return view("edititem", [
            "product" => User::where("id", $id)->first()
        ]);
    }
    public function update(Request $request, $id){
        $validatedData = $request->validate([
            "product_name" => "required|min:3",
            "price"=>"required|numeric|min:0|not_in:0",
            "variant" => "required|min:3",
            "stock" => "required|numeric|min:0|not_in:0",
            "unit"=>"required"
        ]);
        User::where("id", $id)->update($validatedData);
        return redirect("/dashboard")->with("successEditProduct", "Product editted successfully!");

    }
    public function destroy($id){
        User::destroy("id", $id);
        return redirect("/dashboard")->with("successDeleteProduct", "Product deleted successfully!");
    }
}
