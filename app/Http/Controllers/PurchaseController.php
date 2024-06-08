<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Purchase;
use App\Models\Pembelian;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(){
        return view("pages.purchase.index", [
            "purchases" => Purchase::all()
        ]);
    }

    public function create(){
        return view("pages.purchase.create");
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

        Purchase::create($validatedData);
        return redirect(route("purchase-index"))->with("successAddProduct", "Product added successfully!");


    }
    public function edit($id){


        return view("pages.purchase.edit", [
            "product" => Purchase::where("id", $id)->first()
        ]);
    }
    public function viewitem($id){
        $purchase = Purchase::where("id", $id)->first();
        $products = $purchase->products;

        return view("pages.purchase.viewitem", [
            "purchase" => $purchase,
            "products" => $products
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
        Purchase::where("id", $id)->update($validatedData);
        return redirect(route("purchase-index"))->with("successEditProduct", "Product editted successfully!");

    }
    public function destroy($id){
        Purchase::destroy("id", $id);
        return redirect(route("purchase-index"))->with("successDeleteProduct", "Product deleted successfully!");
    }
}
