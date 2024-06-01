<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller{

    public function index(){
        return view("pages.product.index", [
            "products" => Product::all()
        ]);
    }
//create untuk kasih tampilan formnya
    public function create(){
        return view("pages.product.create");
    }



// store untuk memasukan data
    public function store(Request $request){
        $validatedData = $request->validate([
            "product_name" => "required|min:3",
            "price"=>"required|numeric|min:0|not_in:0",
            "variant" => "required|min:3",
            "stock" => "required|numeric|min:0|not_in:0",
            "discount" => "nullable|numeric",
            "status" => "required|min:3",
            "product_code" => "required|min:3",
            "unit"=>"required"

        ]);


        // $user = User::where("name", session("logged_in_user"))->first();
        // $validatedData["user_id"] = $user->id;

        Product::create($validatedData);
        return redirect(route("product-index"))->with("successAddProduct", "Product added successfully!");


    }
    public function edit($id){
        return view("pages.product.edit", [
            "product" => Product::where("id", $id)->first(),
            "status" => ["Ready", "Out Of Stock"]
        ]);
    }

    public function update(Request $request, $id){
        $validatedData = $request->validate([
            "product_name" => "required|min:3",
            "price"=>"required|numeric|min:0|not_in:0",
            "variant" => "required|min:3",
            "stock" => "required|numeric|min:0|not_in:0",
            "discount" => "nullable|numeric",
            "status" => "required|min:3",
            "product_code" => "required|min:3",
            "unit"=>"required"
        ]);
        Product::where("id", $id)->update($validatedData);
        return redirect(route("product-index"))->with("successEditProduct", "Product editted successfully!");

    }
    public function destroy($id){
        Product::destroy("id", $id);
        return redirect(route("product-index"))->with("successDeleteProduct", "Product deleted successfully!");
    }
}
