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
            "return_items" => ReturnItem::where('archived', 0)->get()
        ]);
    }

    public function create(){
        return view("pages.return-item.create", [
            "projects" => Project::where('archived', 0)->get(),
            "products" => Product::where('archived', 0)->get()
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

        // try {
        //     DB::beginTransaction();

        //     $project = Project::find($validated_data["project"]);
        //     $product = Product::find($validated_data["product"]);

        //     if($request->file("image")){
        //         $validated_data["foto"] = $request->file("image")->store("images");
        //         unset($validated_data["image"]);
        //     }

        //     $pcode = str_replace(' ', '', ucwords(strtolower($product->product_name))) . "/" . str_replace(' ', '', ucwords(strtolower($product->variant)));

        //     // Check if the exact same product with the same attributes exists (excluding the return status)
        //     $existingProduct = Product::where('product_name', $product->product_name)
        //         ->where('variant', $product->variant)
        //         ->where('price', $product->price)
        //         ->where('discount', $product->discount)
        //         ->where('is_returned', 'yes')
        //         ->first();

        //     if ($existingProduct) {
        //         // If an existing returned product is found, update the stock
        //         $existingProduct->stock += $validated_data["qty"];
        //         $existingProduct->save();

        //         $aidi = $existingProduct->id;

        //     } else {
        //         // If no existing returned product is found, create a new one
        //         // Check if the same product exists without considering the "is_returned" status
        //         $product_with_existing_pcode = Product::where("product_code", "like", $pcode . "%")->get();

        //         // Generate a new unique product_code by appending the count of existing products
        //         $newProductCode = $pcode . ($product_with_existing_pcode->count() + 1); // Ensure unique product code

        //         // Create a new product entry with the is_returned set to "yes"
        //         $newProd = Product::create([
        //             "product_name" => $product->product_name,
        //             "variant" => $product->variant,
        //             "price" => $product->price,
        //             "discount" => $product->discount,
        //             "unit" => $product->unit,
        //             "stock" => $validated_data["qty"], // Stock is set to the quantity being returned
        //             "status" => $product->status,
        //             "markup" => $product->markup,
        //             "product_code" => $newProductCode,
        //             "is_returned" => "yes"
        //         ]);

        //         $aidi = $newProd->id;
        //     }

        //     ReturnItem::create([
        //         "project_id" => $project->id,
        //         "product_id" => $aidi,
        //         "foto" => $validated_data["foto"],
        //         "PIC" => $validated_data["PIC"],
        //         "status" => $validated_data["status"],
        //         "quantity" => $validated_data["qty"]
        //     ]);

        //     DB::commit();
        // }
        // catch(Exception $e){
        //     DB::rollback();

        //     return $e;
        // }

        ReturnItem::create($validated_data);

        return redirect(route("returnitem-index"))->with("successAddReturnItem", "Berhasil menambahkan data pengembalian barang baru.");
    }

    public function edit($id){
        return view("pages.return-item.edit", [
            "return_item" => ReturnItem::find($id),
            "projects" => Project::where('archived', 0)->get(),
            "products" => Product::where('archived', 0)->get()
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
            $return_item->update(["archived" => 1]);
        }
        else {
            $existingReturnedProduct->update(["archived" => 1]);
            $return_item->update(["archived" => 1]);
        }

        return redirect(route("returnitem-index"))->with("successDeleteReturnItem", "Berhasil menghapus data pengembalian barang.");
    }

}

