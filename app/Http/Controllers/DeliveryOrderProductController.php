<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\DeliveryOrder;
use Illuminate\Http\Request;
use App\Models\DeliveryOrderProduct;

class DeliveryOrderProductController extends Controller
{
    // To show existing products in a delivery order
    public function view_items($id){
        $deliveryorder = DeliveryOrder::where("id", $id)->first();
        $do = DeliveryOrderProduct::where("delivery_order_id", $deliveryorder->id)->get();


        return view("pages.transit.deliveryorderproduct.index", [
            "deliveryorder" => $deliveryorder,
            "do"=> $do

        ]);
    }

    // To add an existing product to a delivery order
    public function add_existing_product($id){
        $deliveryorder = DeliveryOrder::where("id", $id)->first();
        return view("pages.transit.deliveryorderproduct.adddeliveryorder", [
            "deliveryorder" => $deliveryorder,
            "products" => Product::all()
        ]);
    }

    // To store the existing product to the delivery order
    public function store_existing_product(Request $request, $id){
        $request->validate([
            "products" => "required",
            "quantities" => "required",

        ]);

        $deliveryorder = DeliveryOrder::where("id",$id)->first();
        foreach($request->products as $index=>$product_id){
            DeliveryOrderProduct::create([
                "delivery_order_id" => $deliveryorder->id,
                "product_id" => $product_id,

                "quantity" => $request->quantities[$index],

            ]);

            $oldstock = Product::where("id",$product_id)->first()->stock;
            Product::where("id",$product_id)->update([
                "stock" => $oldstock - $request->quantities[$index],

            ]);
        };


        return redirect(route("deliveryorderproduct-viewitem", $deliveryorder->id));

    }

    // To remove a product from a delivery order
    public function destroy($id, $did){
        $do = DeliveryOrderProduct::where("id", $did)->first();

        $oldstock = $do->product->stock;
        Product::where("id", $do->product->id)->update([
            "stock" => $oldstock + $do->quantity
        ]);

        DeliveryOrderProduct::destroy("id", $do->id);

        return redirect(route("deliveryorderproduct-viewitem", $id))->with("successDeleteProduct", "Product deleted successfully!");
    }
}
