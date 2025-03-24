<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Project;
use App\Models\ReturnItem;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Models\ReturnItemProduct;
use Illuminate\Support\Facades\DB;
use App\Models\DeliveryOrderProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Return_;

class ReturnItemController extends Controller {
    public function index(){
        return view("pages.return-item.index", [
            "return_items" => ReturnItem::filter(request(['search']))->orderBy('return_date', 'desc')->paginate(30)
        ]);
    }

    public function create(){
        return view("pages.return-item.create", [
            "projects" => Project::all(),
            "products" => Product::all()
        ]);
    }

    public function store(Request $request){
        $validated_data = $request->validate([
            'return_date' => 'required',
            "status" => "required",
            "PIC" => "required|min:3",
            'driver' => 'required|min:3',
            "project_id" => "required",
        ]);

        ReturnItem::create($validated_data);

        return redirect(route("returnitem-index"))->with("successAddReturnItem", "Berhasil menambahkan data pengembalian barang baru.");
    }

    public function edit($id){
        return view("pages.return-item.edit", [
            "return_item" => ReturnItem::find($id),
            "projects" => Project::all(),
            "products" => Product::all()
        ]);
    }

    public function update(Request $request, $id){
        $validated_data = $request->validate([
            'return_date' => 'required',
            "status" => "required",
            "PIC" => "required|min:3",
            'driver' => 'required|min:3',
            "project_id" => "required",
        ]);

        $return_item = ReturnItem::find($id);

        // $validation_rule = [
        //     "status" => "required",
        //     "PIC" => "required|min:3",
        //     "quantity" => "required|numeric|min:0|not_in:0"
        // ];

        // if(!$return_item->foto){
        //     $validation_rule["image"] = "required|image";
        // }
        // else {
        //     $validation_rule["image"] = "nullable|image";
        // }

        // $validated_data = $request->validate($validation_rule);

        // if($request->file("image")){
        //     if($return_item->foto && $return_item->foto != ""){
        //         Storage::delete($return_item->foto);
        //     }

        //     $validated_data["foto"] = $request->file("image")->store("images");
        //     unset($validated_data["image"]);
        // }

        $return_item->update($validated_data);

        return redirect(route("returnitem-index"))->with("successEditReturnItem", "Berhasil memperbaharui data pengembalian barang.");
    }

    public function destroy($id){
        $return_item = ReturnItem::find($id);
        $existingReturnedProduct = Product::find($return_item->product->id);

        if($return_item->quantity < $existingReturnedProduct->stock){
            $prevStock = $existingReturnedProduct->stock;
            $existingReturnedProduct->update(["stock" => $prevStock - $return_item->quantity]);
            $return_item->delete();
        }
        else {
            $existingReturnedProduct->delete();
            $return_item->delete();
        }

        return redirect(route("returnitem-index"))->with("successDeleteReturnItem", "Berhasil menghapus data pengembalian barang.");
    }

}

