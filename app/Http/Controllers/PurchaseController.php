<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Partner;
use App\Models\Purchase;
use App\Models\Pembelian;
use App\Models\Product;
use App\Models\PurchaseProduct;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(){
        return view("pages.purchase.index", [
            "purchases" => Purchase::all()
        ]);
    }

    public function create(){
        return view("pages.purchase.create", [
            "supplier" => Partner::all(),
            "status" => ["Complete", "Incomplete"],
            "purchases" => Purchase::all()
        ]
    );

    }

    public function store(Request $request){
        $validatedData = $request->validate([
            // "product_name" => "required|min:3",
            "purchase_date" => "required",
            "purchase_deadline" => "required",
            "register" => "required|min:3",
            "partner_id" => "required",
            "purchase_status" => "required"
        ]);


        // $user = User::where("name", session("logged_in_user"))->first();
        // $validatedData["user_id"] = $user->id;

        Purchase::create($validatedData);
        return redirect(route("purchase-index"))->with("successAddPurchase", "Purchase added successfully!");


    }

    public function edit($id){
        return view("pages.purchase.edit", [
            "purchase" => Purchase::where("id", $id)->first(),
            "supplier" => Partner::all(),
            "status" => ["Complete", "Incomplete"],
            "purchases" => Purchase::all()
        ]);
    }

    // public function viewitem($id){
    //     $purchase = Purchase::where("id", $id)->first();
    //     $products = $purchase->products;


    //     return view("pages.purchase.viewitem", [
    //         "purchase" => $purchase,
    //         "products" => $products
    //     ]);
    // }

    public function update(Request $request, $id){
        $validatedData = $request->validate([
            // "product_name" => "required|min:3",
            "purchase_date" => "required",
            "purchase_deadline" => "required",
            "register" => "required|min:3",
            "partner_id" => "required",
            "purchase_status" => "required"
        ]);
        Purchase::where("id", $id)->update($validatedData);
        return redirect(route("purchase-index"))->with("successEditProduct", "Product editted successfully!");

    }
    public function destroy($id){
        $pp = PurchaseProduct::where("purchase_id", $id)->get();

        foreach ($pp as $data){
            $product = Product::where("id", $data->product_id)->first();
            $oldstock = $product->stock;
            Product::where("id", $data->product_id)->update(["stock"=> ($oldstock -
            $data->quantity)]);
        }

        Purchase::destroy("id", $id);

        return redirect(route("purchase-index"))->with("successDeleteProduct", "Product deleted successfully!");
    }
}
