<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;

class ProjectController extends Controller{

    public function index(){
        return view("pages.project.index", [

        ]);
    }

    public function create(){
        return view("pages.project.create");
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

        Project::create($validatedData);
        return redirect("/dashboard")->with("successAddProduct", "Product added successfully!");


    }
    public function edit($id){


        return view("pages.delivery-order.edit", [
            "product" => Project::where("id", $id)->first()
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
        Project::where("id", $id)->update($validatedData);
        return redirect("/dashboard")->with("successEditProduct", "Product editted successfully!");

    }
    public function destroy($id){
        Project::destroy("id", $id);
        return redirect("/dashboard")->with("successDeleteProduct", "Product deleted successfully!");
    }
}
