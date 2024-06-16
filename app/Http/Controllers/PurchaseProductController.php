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
        $request->validate([
            "products" => "required",
            "discounts" => "required",
            "quantities" => "required",
            "prices" => "required",
        ]);

        $purchase = Purchase::where("id",$id)->first();
        foreach($request->products as $index=>$product_id){
            PurchaseProduct::create([
                "purchase_id" => $purchase->id,
                "product_id" => $product_id,
                "discount" => $request->discounts[$index],
                "quantity" => $request->quantities[$index],
                "price" => $request->prices[$index]
            ]);

            $oldstock = Product::where("id",$product_id)->first()->stock;
            Product::where("id",$product_id)->update([
                "stock" => $oldstock + $request->quantities[$index],
                "price" => $request->prices[$index]
            ]);
        };


        return redirect(route("purchaseproduct-viewitem", $purchase->id));

        // Purchase::create($validatedData);
        // return redirect(route("pages.transit.purchaseproduct.index"))->with("successAddPurchase", "Purchase added successfully!");



    }

    // To add an unexisting product to a purchase
    public function add_new_product($id){
        $purchase = Purchase::where("id", $id)->first();
        return view("pages.transit.purchaseproduct.addnewitem", [
            "purchase" => $purchase,
        ]);
    }

    // To store the unexisting product to the purchase also adding it to all products
    public function store_new_product(Request $request, $id){
        // pr: tambahin diskontol uhahaha
        $request->validate([
            "product_name" => "required",
            "unit" => "required",
            "status" => "required",
            "variant" => "required",
            "product_code" => "required",
            "price" => "required",
            "markup" => "required",
            "stock" => "required",
        ]);

        $purchase = Purchase::where("id",$id)->first();
        foreach($request->products as $index=>$product_id){
            // bikin new product

            PurchaseProduct::create([
                "purchase_id" => $purchase->id,
                "product_id" => $product_id,
                "discount" => $request->discounts[$index],
                "quantity" => $request->quantities[$index],
                "price" => $request->prices[$index]
            ]);
        };


        return redirect(route("purchaseproduct-viewitem", $purchase->id));
    }
    // To remove a product from a purchase
    public function destroy($id, $pid){
        $pp = PurchaseProduct::where("id", $pid)->first();

        $oldstock = $pp->product->stock;
        Product::where("id", $pp->product->id)->update([
            "stock" => $oldstock - $pp->quantity
        ]);

        PurchaseProduct::destroy("id", $pp->id);

        return redirect(route("purchaseproduct-viewitem", $id))->with("successDeleteProduct", "Product deleted successfully!");
    }
}
