<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;

class DeliveryOrderController extends Controller{

    public function index(){
        return view("pages.delivery-order.index", [

        ]);
    }

    public function create(){
        return view("pages.delivery-order.create");
    }




    public function store(Request $request){
        $validatedData = $request->validate([
            "product_name" => "required|min:1",
            "delivery_date"=>"required|date",
            "project_name" => "required|min:1",
            "register" => "required|numeric|min:0|not_in:0",
            "note"=>"nullable"
        ]);


        // $user = User::where("name", session("logged_in_user"))->first();
        // $validatedData["user_id"] = $user->id;

        DeliveryOrder::create($validatedData);
        return redirect(route("delivery-order-index"))->with("successAddOrder", "Order added successfully!");


    }
    public function edit($id){


        return view("pages.delivery-order.edit", [
            "delivery_order" => DeliveryOrder::where("id", $id)->first()
        ]);
    }
    public function update(Request $request, $id){
        $validatedData = $request->validate([
            "product_name" => "required|min:1",
            "delivery_date"=>"required|date",
            "project_name" => "required|min:1",
            "register" => "required|numeric|min:0|not_in:0",
            "note"=>"nullable"
        ]);
        DeliveryOrder::where("id", $id)->update($validatedData);
        return redirect(route("delivery-order-index"))->with("successEditOrder", "Order editted successfully!");

    }
    public function destroy($id){
        DeliveryOrder::destroy("id", $id);
        return redirect(route("delivery-order-index"))->with("successDeleteOrder", "Order deleted successfully!");
    }
}
