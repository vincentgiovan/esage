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
            "requests" => RequestItem::where('archived', 0)->get()
        ]);
    }

    public function show($id){
        return view("pages.request-item.show", [
            "request_item_products" => RequestItemProduct::where("request_item_id", $id)->get()
        ]);
    }

    public function create(){
        return view("pages.request-item.create", [
            "projects" => Project::where('archived', 0)->get(),
            "products" => Product::where('archived', 0)->get()
        ]);
    }

    public function store(Request $request){
        $reqit = RequestItem::create([
            "notes" => $request->notes,
            "PIC" => $request->PIC,
            "request_date" => $request->request_date,
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
        return view("pages.request-item.edit", [
            "request_item" => RequestItem::find($id),
            "rip" => RequestItemProduct::where("request_item_id", $id)->get(),
            "projects" => Project::where('archived', 0)->get(),
            "products" => Product::where('archived', 0)->get()
        ]);
    }

    public function update(Request $request, $id){
        $reqit = RequestItem::find($id);

        $reqit->update([
            "notes" => $request->notes,
            "PIC" => $request->PIC,
            "request_date" => $request->request_date,
            "project_id" => $request->project_id
        ]);

        $existing_rip = RequestItemProduct::where("request_item_id", $reqit->id)->get();
        foreach($existing_rip as $er){
            RequestItemProduct::find($er->id)->update(["archived" => 1]);
        }

        foreach($request->products as $i => $prd_id){
            RequestItemProduct::create([
                "product_id" => $prd_id,
                "request_item_id" => $reqit->id,
                "quantity" => $request->quantities[$i]
            ]);
        }

        return redirect(route("requestitem-index"))->with("successEditRequest", "Successfully edited the request item");
    }

    public function destroy($id){
        RequestItem::find($id)->update(["archived" => 1]);

        return redirect(route("requestitem-index"))->with("successDeleteRequest", "Successfully deleted the request item");
    }
}
