<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ReturnItem;
use App\Models\RequestItem;
use Illuminate\Http\Request;
use App\Models\ReturnItemImage;
use App\Models\ReturnItemProduct;
use App\Models\RequestItemProduct;
use Exception;
use Illuminate\Support\Facades\DB;
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
            'unvalidated_return_item_products' => ReturnItemProduct::where('status', 'awaiting')->with('return_item', function($query){
                return $query->orderBy('return_date');
            })->orderBy('return_item_id')->get(),

            'validated_return_item_products' => ReturnItemProduct::whereNot('status', 'awaiting')->filter(request(['project']))->with('return_item', function($query){
                return $query->orderBy('return_date');
            })->orderBy('return_item_id')->get()
        ]);
    }

    public function save_unvalids(Request $request)
    {
        $request->validate([
            'rip.*' => 'required',
            'good.*' => 'required',
            'bad.*' => 'required'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->rip as $rip_id) {
                $retitprod = ReturnItemProduct::find($rip_id);
                $product = $retitprod->product;

                $goodQty = $request['good'][$rip_id];
                $badQty = $request['bad'][$rip_id];

                // Define a reusable function to update or create product records
                $processProduct = function ($condition, $qty) use ($product) {
                    if ($qty <= 0) return null; // Skip if no quantity

                    $pcode = str_replace(' ', '', ucwords(strtolower($product->product_name))) . "/" . str_replace(' ', '', ucwords(strtolower($product->variant)));

                    // Check if an existing product with the same attributes exists
                    $existingProduct = Product::where('product_name', $product->product_name)
                        ->where('variant', $product->variant)
                        ->where('price', $product->price)
                        ->where('discount', $product->discount)
                        ->where('condition', $condition)
                        ->first();

                    if ($existingProduct) {
                        // Update stock if product exists
                        $existingProduct->stock += $qty;
                        $existingProduct->save();
                        return $existingProduct->id;
                    } else {
                        // Generate unique product code
                        $existingProductsCount = Product::where("product_code", "like", $pcode . "%")->count();
                        $newProductCode = $pcode . ($existingProductsCount + 1);

                        // Create new product
                        $newProduct = Product::create([
                            "product_name" => $product->product_name,
                            "variant" => $product->variant,
                            "price" => $product->price,
                            "discount" => $product->discount,
                            "unit" => $product->unit,
                            "stock" => $qty,
                            "status" => $qty > 0 ? 'Ready' : 'Out of Stock',
                            "markup" => $product->markup,
                            "product_code" => $newProductCode,
                            "type" => $product->type,
                            "condition" => $condition
                        ]);

                        return $newProduct->id;
                    }
                };

                // Case 1: All returned items are "Good"
                if ($badQty == 0) {
                    $goodProductId = $processProduct('good', $goodQty);
                    $retitprod->update(['status' => 'validated', 'product_id' => $goodProductId]);
                }
                // Case 2: All returned items are "Bad"
                elseif ($goodQty == 0) {
                    $badProductId = $processProduct('degraded', $badQty);
                    $retitprod->update(['status' => 'validated', 'product_id' => $badProductId]);
                }
                // Case 3: Some items are "Good", some are "Bad"
                else {
                    $goodProductId = $processProduct('good', $goodQty);
                    $badProductId = $processProduct('degraded', $badQty);

                    // Update the return item product for the "good" items
                    $retitprod->update([
                        'status' => 'validated',
                        'product_id' => $goodProductId,
                        'qty' => $goodQty
                    ]);

                    // Create a new return record for the "bad" items
                    ReturnItemProduct::create([
                        'return_item_id' => $retitprod->return_item->id,
                        'product_id' => $badProductId,
                        'qty' => $badQty,
                        'status' => 'validated'
                    ]);
                }
            }
            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();

            throw $e;
        }

        return redirect(route('returnitem-conditionvalidation'))->with('successValidateCondition', 'Berhasil menyimpan data validasi kondisi barang pengembalian');
    }


    // public function save_unvalids(Request $request){
    //     $request->validate([
    //         'rip.*' => 'required',
    //         'good.*' => 'required',
    //         'bad.*' => 'required'
    //     ]);

    //     foreach($request->rip as $i => $rip_id){
    //         $retitprod = ReturnItemProduct::find($rip_id);
    //         $product = $retitprod->product;

    //         // If all returned items are good
    //         if($request['bad'][$rip_id] == 0){
    //             $pcodeGood = str_replace(' ', '', ucwords(strtolower($product->product_name))) . "/" . str_replace(' ', '', ucwords(strtolower($product->variant)));

    //             // Check if the exact same product with the same attributes exists (excluding the return status)
    //             $existingProductGood = Product::where('product_name', $product->product_name)
    //                 ->where('variant', $product->variant)
    //                 ->where('price', $product->price)
    //                 ->where('discount', $product->discount)
    //                 ->where('condition', 'good')
    //                 ->first();

    //             if ($existingProductGood) {
    //                 // If an existing returned product is found, update the stock
    //                 $existingProductGood->stock += $retitprod->qty;
    //                 $existingProductGood->save();

    //                 $aidiGood = $existingProductGood->id;
    //             } else {
    //                 // If no existing returned product is found, create a new one
    //                 // Check if the same product exists without considering the "condition" status
    //                 $product_with_existing_pcode = Product::where("product_code", "like", $pcodeGood . "%")->get();

    //                 // Generate a new unique product_code by appending the count of existing products
    //                 $newProductCodeGood = $pcodeGood . ($product_with_existing_pcode->count() + 1); // Ensure unique product code

    //                 // Create a new product entry with the condition set to "degraded"/"good"
    //                 $newProdGood = Product::create([
    //                     "product_name" => $product->product_name,
    //                     "variant" => $product->variant,
    //                     "price" => $product->price,
    //                     "discount" => $product->discount,
    //                     "unit" => $product->unit,
    //                     "stock" => $retitprod->qty, // Stock is set to the quantity being returned
    //                     "status" => $retitprod->qty > 0? 'Ready' : 'Out of Stock',
    //                     "markup" => $product->markup,
    //                     "product_code" => $newProductCodeGood,
    //                     "type" => $product->type,
    //                     "condition" => 'good'
    //                 ]);

    //                 $aidiGood = $newProdGood->id;
    //             }

    //             $retitprod->update(['status' => 'validated', 'product_id' => $aidiGood]);
    //         }

    //         // If all items returned are bad
    //         else if($request['good'][$rip_id] == 0){
    //             $pcodeBad = str_replace(' ', '', ucwords(strtolower($product->product_name))) . "/" . str_replace(' ', '', ucwords(strtolower($product->variant)));

    //             // Check if the exact same product with the same attributes exists (excluding the return status)
    //             $existingProductBad = Product::where('product_name', $product->product_name)
    //                 ->where('variant', $product->variant)
    //                 ->where('price', $product->price)
    //                 ->where('discount', $product->discount)
    //                 ->where('condition', 'degraded')
    //                 ->first();

    //             if ($existingProductBad) {
    //                 // If an existing returned product is found, update the stock
    //                 $existingProductBad->stock += $retitprod->qty;
    //                 $existingProductBad->save();

    //                 $aidiBad = $existingProductBad->id;
    //             } else {
    //                 // If no existing returned product is found, create a new one
    //                 // Check if the same product exists without considering the "condition" status
    //                 $product_with_existing_pcode = Product::where("product_code", "like", $pcodeBad . "%")->get();

    //                 // Generate a new unique product_code by appending the count of existing products
    //                 $newProductCodeBad = $pcodeBad . ($product_with_existing_pcode->count() + 1); // Ensure unique product code

    //                 // Create a new product entry with the condition set to "degraded"/"Bad"
    //                 $newProdBad = Product::create([
    //                     "product_name" => $product->product_name,
    //                     "variant" => $product->variant,
    //                     "price" => $product->price,
    //                     "discount" => $product->discount,
    //                     "unit" => $product->unit,
    //                     "stock" => $retitprod->qty, // Stock is set to the quantity being returned
    //                     "status" => $retitprod->qty > 0? 'Ready' : 'Out of Stock',
    //                     "markup" => $product->markup,
    //                     "product_code" => $newProductCodeBad,
    //                     "type" => $product->type,
    //                     "condition" => 'degraded'
    //                 ]);

    //                 $aidiBad = $newProdBad->id;
    //             }

    //             $retitprod->update(['status' => 'validated', 'product_id' => $aidiBad]);
    //         }

    //         // If some of the returned items are good and some of them are bad
    //         else {
    //             $pcodeGood = str_replace(' ', '', ucwords(strtolower($product->product_name))) . "/" . str_replace(' ', '', ucwords(strtolower($product->variant)));

    //             // Check if the exact same product with the same attributes exists (excluding the return status)
    //             $existingProductGood = Product::where('product_name', $product->product_name)
    //                 ->where('variant', $product->variant)
    //                 ->where('price', $product->price)
    //                 ->where('discount', $product->discount)
    //                 ->where('condition', 'good')
    //                 ->first();

    //             if ($existingProductGood) {
    //                 // If an existing returned product is found, update the stock
    //                 $existingProductGood->stock += $retitprod->qty;
    //                 $existingProductGood->save();

    //                 $aidiGood = $existingProductGood->id;
    //             } else {
    //                 // If no existing returned product is found, create a new one
    //                 // Check if the same product exists without considering the "condition" status
    //                 $product_with_existing_pcode = Product::where("product_code", "like", $pcodeGood . "%")->get();

    //                 // Generate a new unique product_code by appending the count of existing products
    //                 $newProductCodeGood = $pcodeGood . ($product_with_existing_pcode->count() + 1); // Ensure unique product code

    //                 // Create a new product entry with the condition set to "degraded"/"good"
    //                 $newProdGood = Product::create([
    //                     "product_name" => $product->product_name,
    //                     "variant" => $product->variant,
    //                     "price" => $product->price,
    //                     "discount" => $product->discount,
    //                     "unit" => $product->unit,
    //                     "stock" => $request['good'][$rip_id], // Stock is set to the quantity being returned
    //                     "status" => $request['good'][$rip_id] > 0? 'Ready' : 'Out of Stock',
    //                     "markup" => $product->markup,
    //                     "product_code" => $newProductCodeGood,
    //                     "type" => $product->type,
    //                     "condition" => 'good'
    //                 ]);

    //                 $aidiGood = $newProdGood->id;
    //             }

    //             $retitprod->update(['status' => 'validated', 'product_id' => $aidiGood]);

    //             $pcodeBad = str_replace(' ', '', ucwords(strtolower($product->product_name))) . "/" . str_replace(' ', '', ucwords(strtolower($product->variant)));

    //             // Check if the exact same product with the same attributes exists (excluding the return status)
    //             $existingProductBad = Product::where('product_name', $product->product_name)
    //                 ->where('variant', $product->variant)
    //                 ->where('price', $product->price)
    //                 ->where('discount', $product->discount)
    //                 ->where('condition', 'degraded')
    //                 ->first();

    //             if ($existingProductBad) {
    //                 // If an existing returned product is found, update the stock
    //                 $existingProductBad->stock += $retitprod->qty;
    //                 $existingProductBad->save();

    //                 $aidiBad = $existingProductBad->id;
    //             } else {
    //                 // If no existing returned product is found, create a new one
    //                 // Check if the same product exists without considering the "condition" status
    //                 $product_with_existing_pcode = Product::where("product_code", "like", $pcodeBad . "%")->get();

    //                 // Generate a new unique product_code by appending the count of existing products
    //                 $newProductCodeBad = $pcodeBad . ($product_with_existing_pcode->count() + 1); // Ensure unique product code

    //                 // Create a new product entry with the condition set to "degraded"/"Bad"
    //                 $newProdBad = Product::create([
    //                     "product_name" => $product->product_name,
    //                     "variant" => $product->variant,
    //                     "price" => $product->price,
    //                     "discount" => $product->discount,
    //                     "unit" => $product->unit,
    //                     "stock" => $request['bad'][$rip_id], // Stock is set to the quantity being returned
    //                     "status" => $request['bad'][$rip_id] > 0? 'Ready' : 'Out of Stock',
    //                     "markup" => $product->markup,
    //                     "product_code" => $newProductCodeBad,
    //                     "type" => $product->type,
    //                     "condition" => 'degraded'
    //                 ]);

    //                 $aidiBad = $newProdBad->id;
    //             }

    //             ReturnItem::create([
    //                 'return_item_id' => $retitprod->return_item->id,
    //                 'product_id' => $aidiBad,
    //                 'qty' => $request['bad'][$rip_id],
    //             ]);
    //         }
    //     }

    //     return redirect(route('returnitem-conditionvalidation'))->with('successValidateCondition', 'Berhasil menyimpan data validasi kondisi barang pengembalian');
    // }
}
