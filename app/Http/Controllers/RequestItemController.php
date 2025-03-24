<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Project;
use App\Models\RequestItem;
use Illuminate\Http\Request;
use App\Models\RequestItemProduct;
use Illuminate\Support\Facades\Auth;

class RequestItemController extends Controller
{
    public function index(){
        return view("pages.request-item.index", [
            "awaiting_requests" => RequestItem::where('status', 'awaiting')->orderBy('request_date', 'desc')->paginate(30),
            "unawaiting_requests" => RequestItem::whereNot('status', 'awaiting')->orderBy('request_date', 'desc')->paginate(30)
        ]);
    }

    public function show($id){
        return view("pages.request-item.show", [
            'request_item' => RequestItem::find($id),
            "request_item_products" => RequestItemProduct::where("request_item_id", $id)->get()
        ]);
    }

    public function create(){
        return view("pages.request-item.create", [
            "projects" => Project::all(),
            "products" => Product::whereNot('condition', 'degraded')->get()
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

        return redirect(route("requestitem-index"))->with("successAddRequest", "Berhasil menambahkan request barang baru.");
    }

    public function edit($id){
        $reqit = RequestItem::find($id);

        if($reqit->status != 'awaiting'){
            return back();
        }
        else {
            return view("pages.request-item.edit", [
                "request_item" => RequestItem::find($id),
                "rip" => RequestItemProduct::where("request_item_id", $id)->get(),
                "projects" => Project::all(),
                "products" => Product::all()
            ]);
        }
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
            RequestItemProduct::find($er->id)->delete();
        }

        foreach($request->products as $i => $prd_id){
            RequestItemProduct::create([
                "product_id" => $prd_id,
                "request_item_id" => $reqit->id,
                "quantity" => $request->quantities[$i]
            ]);
        }

        return redirect(route("requestitem-index"))->with("successEditRequest", "Berhasil memperbaharui data request barang.");
    }

    public function update_status(Request $request, $id){
        $status = '';
        switch($request->status){
            case 'Setujui': $status = 'approved'; break;
            case 'Tolak': $status = 'rejected'; break;
        }

        RequestItem::find($id)->update(['status' => $status]);

        return redirect(route('requestitem-index'))->with('successUpdateStatus', 'Berhasil ' . ($status == 'approved'? 'menyetujui' : 'menolak') . 'request barang.');
    }

    public function destroy($id){
        $reqit = RequestItem::find($id);

        if($reqit->status != 'awaiting'){
            return back();
        }
        else {
            $reqit->delete();
            return redirect(route("requestitem-index"))->with("successDeleteRequest", "Berhasil menghapus data request barang.");
        }
    }
}
