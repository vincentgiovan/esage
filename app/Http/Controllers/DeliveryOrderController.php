<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Models\Partner;
use App\Models\Purchase;

class DeliveryOrderController extends Controller{

    public function index(){
        return view("pages.delivery-order.index", [
            "deliveryorders" => DeliveryOrder::all()

        ]);
    }

    public function create(){
        return view("pages.delivery-order.create", [
            "products" => Product::all(),
            "projects" => Project::all()

        ]);
    }




    public function store(Request $request){
        $validatedData = $request->validate([
            // "product_id" => "required",
            "delivery_date"=>"required|date",
            "project_id" => "required",
            "register" => "required|min:0|not_in:0",
            "delivery_status" => "required",
            "note"=>"nullable"
        ]);



        DeliveryOrder::create($validatedData);
        return redirect(route("deliveryorder-index"))->with("successAddOrder", "Order added successfully!");


    }
    public function edit($id){


        return view("pages.delivery-order.edit", [
            "delivery_order" => DeliveryOrder::where("id", $id)->first(),
            // "products" => Product::all(),
            "projects" => Project::all(),
            "status"=> ["complete", "incomplete"]
        ]);
    }
    public function update(Request $request, $id){
        $validatedData = $request->validate([
            // "product_id" => "required|min:1",
            "delivery_date"=>"required|date",
            "project_id" => "required|min:1",
            "register" => "required|min:0|not_in:0",
            "delivery_status" => "required",
            "note"=>"nullable"
        ]);
        DeliveryOrder::where("id", $id)->update($validatedData);
        return redirect(route("deliveryorder-index"))->with("successEditOrder", "Order editted successfully!");

    }
    public function destroy($id){
        DeliveryOrder::destroy("id", $id);
        return redirect(route("deliveryorder-index"))->with("successDeleteOrder", "Order deleted successfully!");
    }
}
