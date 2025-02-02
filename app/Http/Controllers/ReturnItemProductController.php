<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ReturnItem;
use App\Models\RequestItem;
use Illuminate\Http\Request;
use App\Models\ReturnItemImage;
use App\Models\ReturnItemProduct;
use App\Models\RequestItemProduct;
use Illuminate\Support\Facades\Storage;

class ReturnItemProductController extends Controller
{
    public function view_items($id){
        return view('pages.transit.returnitemproduct.index', [
            'return_item' => ReturnItem::find($id)
        ]);
    }

    public function add_list($id){
        return view('pages.transit.returnitemproduct.addnewitem', [
            'return_item' => ReturnItem::find($id),
            'products' => Product::where('archived', 0)->get()
        ]);
    }

    public function store_list(Request $request, $id){
        $request->validate([
            'product_id' => 'required',
            'qty' => 'required'
        ]);

        $return_item = ReturnItem::find($id);

        foreach($request->product_id as $i => $rpid){
            $product = Product::find($rpid);

            ReturnItemProduct::create([
                'product_id' => $product->id,
                'qty' => $request->qty[$i],
                'return_item_id' => $return_item->id
            ]);
        }

        return redirect(route('returnitem-list-view', $return_item->id))->with('successAddItem', 'Berhasil menambahkan barang ke pengembalian.');
    }

    public function remove_list($id){
        $rip = ReturnItemProduct::find($id);
        $return_item_id = $rip->return_item_id;

        $old_stock = $rip->product->stock;
        $new_stock = $old_stock - $rip->qty;

        $rip->product->update(['stock' => $new_stock, 'status' => $new_stock == 0? 'Out of Stock' : 'Ready']);

        $rip->delete();

        return redirect(route('returnitem-list-view', $return_item_id))->with('successRemoveItem', 'Berhasil menghapus barang dari pengembalian.');
    }

    public function add_image($id){
        return view('pages.transit.returnitemproduct.uploadimages', [
            'return_item' => ReturnItem::find($id)
        ]);
    }

    public function store_image(Request $request, $id){
        $request->validate([
            'image.*' => 'required|image|max:4096'
        ]);

        $return_item = ReturnItem::find($id);

        foreach ($request->file('image') as $r_image) {
            $img_path = $r_image->store("return-items", "public"); // Store in 'storage/app/public/return-items'

            ReturnItemImage::create([
                'return_image_path' => $img_path,
                'return_item_id' => $return_item->id
            ]);
        }

        return redirect(route('returnitem-list-view', $return_item->id))->with('successAddImages', 'Berhasil menambahkan foto ke pengembalian.');
    }

    public function remove_image($id){
        $rii = ReturnItemImage::find($id);
        $return_item_id = $rii->return_item_id;

        // hayo hapus juga image dari storage jangan lupa
        Storage::delete($rii->return_image_path);

        $rii->delete();

        return redirect(route('returnitem-list-view', $return_item_id))->with('successRemoveItem', 'Berhasil menghapus foto dari pengembalian.');
    }

    public function condition_validation(){
        return view('pages.return-item.condition-validation', [
            'unvalidated_return_item_products' => ReturnItemProduct::where('status', 'awaiting')->whereHas('return_item', function($query){
                return $query->orderBy('return_date');
            })->orderBy('return_item_id')->get(),

            'validated_return_item_products' => ReturnItemProduct::whereNot('status', 'awaiting')->whereHas('return_item', function($query){
                return $query->orderBy('return_date');
            })->orderBy('return_item_id')->get()
        ]);
    }

    public function save_unvalids(Request $request){
        $request->validate([
            'rip.*' => 'required',
            'conditions.*' => 'required'
        ]);

        foreach($request->rip as $i => $rip_id){
            $reqitprod = ReturnItemProduct::find($rip_id);
            $product = $reqitprod->product;
            $pcondition = ($request->conditions[$i] == 'on')? 'good' : 'degraded';

            $pcode = str_replace(' ', '', ucwords(strtolower($product->product_name))) . "/" . str_replace(' ', '', ucwords(strtolower($product->variant)));

            // Check if the exact same product with the same attributes exists (excluding the return status)
            $existingProduct = Product::where('product_name', $product->product_name)
                ->where('variant', $product->variant)
                ->where('price', $product->price)
                ->where('discount', $product->discount)
                ->where('condition', $pcondition)
                ->first();

            if ($existingProduct) {
                // If an existing returned product is found, update the stock
                $existingProduct->stock += $reqitprod->qty;
                $existingProduct->save();

                $aidi = $existingProduct->id;
            } else {
                // If no existing returned product is found, create a new one
                // Check if the same product exists without considering the "condition" status
                $product_with_existing_pcode = Product::where("product_code", "like", $pcode . "%")->get();

                // Generate a new unique product_code by appending the count of existing products
                $newProductCode = $pcode . ($product_with_existing_pcode->count() + 1); // Ensure unique product code

                // Create a new product entry with the condition set to "degraded"/"good"
                $newProd = Product::create([
                    "product_name" => $product->product_name,
                    "variant" => $product->variant,
                    "price" => $product->price,
                    "discount" => $product->discount,
                    "unit" => $product->unit,
                    "stock" => $reqitprod->qty, // Stock is set to the quantity being returned
                    "status" => $reqitprod->qty > 0? 'Ready' : 'Out of Stock',
                    "markup" => $product->markup,
                    "product_code" => $newProductCode,
                    "type" => $product->type,
                    "condition" => $pcondition
                ]);

                $aidi = $newProd->id;
            }

            $reqitprod->update(['status' => 'validated', 'product_id' => $aidi]);
        }

        return redirect(route('returnitem-conditionvalidation'))->with('successValidateCondition', 'Berhasil menyimpan data validasi kondisi barang pengembalian');
    }
}
