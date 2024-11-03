<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Project;
use App\Models\RequestItem;
use Illuminate\Http\Request;
use App\Models\RequestItemProduct;

class RequestItemController extends Controller
{
    public function index(){
        return view("pages.request-item.index", [
            "requests" => RequestItemProduct::all()
        ]);
    }

    public function create(){
        return view("pages.request-item.create", [
            "projects" => Project::all(),
            "products" => Product::all()
        ]);
    }

    public function store(Request $request){
        $reqit = RequestItem::create([
            "notes" => $request->notes,
            "project_id" => $request->project_id
        ]);

        foreach($request->products as $i => $prd_id){
            RequestItemProduct::create([
                "product_id" => $prd_id,
                "request_item_id" => $reqit->id,
                "quantity" => $request->quantities[$i]
            ]);
        }

        return redirect(route("requestitem-index"))->with("successAddRequest", "Successfully added new request item");
    }

    public function edit($id){

    }

    public function update(Request $request, $id){
        return $request;
    }

    public function destroy($id){

    }
}
