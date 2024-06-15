<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\PurchaseProduct;

class PurchaseProductController extends Controller
{
    // To show existing products in a purchase
    public function view_items($id){
        $purchase = Purchase::where("id", $id)->first();
        $pp = PurchaseProduct::where("purchase_id", $purchase->id)->get();


        return view("pages.transit.purchaseproduct.index", [
            "purchase" => $purchase,
            "pp"=> $pp

        ]);
    }

    // To add an existing product to a purchase
    public function add_existing_product($id){
        $purchase = Purchase::where("id", $id)->first();
        return view("pages.transit.purchaseproduct.addpurchase", [
            "purchase" => $purchase,
            "products" => Product::all()
        ]);
    }


    // To store the existing product to the purchase
    public function store_existing_product(Request $request, $id){

        $validatedData = $request->validate([
            "product_name" => "required",
            "price" => "required|numeric|not_in:0,min:1",
            "discount" => "nullable|numeric|not_in:0",
            "quantity" => "required|numeric|not_in:0,min:1"

        ]);

        return $validatedData;

        // Purchase::create($validatedData);
        // return redirect(route("pages.transit.purchaseproduct.index"))->with("successAddPurchase", "Purchase added successfully!");



    }

    // To add an unexisting product to a purchase
    public function add_new_product($id){

    }

    // To store the unexisting product to the purchase also adding it to all products
    public function store_new_product(Request $request, $id){

    }

    // To remove a product from a purchase
    public function destroy($id){
        $pp = PurchaseProduct:: where("product_id", $id)->first();
        $purchase=Purchase::where("id", $id)->first();
        PurchaseProduct::destroy("id", $pp->id);
        return redirect(route("purchaseproduct-viewitem",$purchase->id))->with("successDeleteProduct", "Product deleted successfully!");
    }
}
