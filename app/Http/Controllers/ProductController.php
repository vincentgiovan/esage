<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller{

    public function index(){
        return view("pages.product.index", [

        ]);
    }

    public function create(){
        return view("pages.product.create");
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

        Product::create($validatedData);
        return redirect("/dashboard")->with("successAddProduct", "Product added successfully!");


    }
    public function edit($id){


        return view("pages.delivery-order.edit", [
            "product" => Product::where("id", $id)->first()
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
        Product::where("id", $id)->update($validatedData);
        return redirect("/dashboard")->with("successEditProduct", "Product editted successfully!");

    }
    public function destroy($id){
        Product::destroy("id", $id);
        return redirect("/dashboard")->with("successDeleteProduct", "Product deleted successfully!");
    }
}
