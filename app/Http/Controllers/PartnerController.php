<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Partner;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;

class PartnerController extends Controller{

    public function index(){
        return view("pages.partner.index", [
            "partners" => Partner::all()
        ]);
    }

    public function create(){
        return view("pages.partner.create");
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            "partner_name" => "required|min:1",
            "role"=>"required",
            "remark" => "nullable",
            "address" => "required|min:0",
            "contact"=>"required|numeric|min:7|not_in:0",
            "phone"=>"required|numeric|min:1|not_in:0",
            "fax"=>"required|numeric|min:1|not_in:0",
            "email" => "required|email:dns",
            "tempo" => "nullable"
        ]);


        // $user = User::where("name", session("logged_in_user"))->first();
        // $validatedData["user_id"] = $user->id;

        Partner::create($validatedData);
        return redirect(route("partner-index"))->with("successAddPartner", "Partner added successfully!");


    }
    public function edit($id){


        return view("pages.partner.edit", [
            "partner" =>Partner::where("id", $id)->first()
        ]);
    }
    public function update(Request $request, $id){
        $validatedData = $request->validate([
            "partner_name" => "required|min:1",
            "role"=>"required",
            "remark" => "nullable",
            "address" => "required|min:0",
            "contact"=>"required|min:7|not_in:0",
            "phone"=>"required|min:1|not_in:0",
            "fax"=>"required|min:1|not_in:0",
            "email" => "required|email:dns",
            "tempo" => "nullable"
        ]);
        Partner::where("id", $id)->update($validatedData);
        return redirect(route("partner-index"))->with("successEditPartner", "Partner editted successfully!");

    }
    public function destroy($id){
        Partner::destroy("id", $id);
        return redirect(route("partner-index"))->with("successDeletePartner", "Partner deleted successfully!");
    }
}
