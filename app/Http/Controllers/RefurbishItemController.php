<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RefurbishItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefurbishItemController extends Controller
{
    public function index(){
        return view('pages.refurbish-item.index', [
            'refurbish_items' => RefurbishItem::orderBy('refurbish_date', 'desc')->paginate(30)
        ]);
    }

    public function create(){
        return view('pages.refurbish-item.create', [
            'products' => Product::where('condition', 'degraded')->where('stock', '>', 0)->get()
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'product_id' => 'required',
            'refurbish_date' => 'required',
            'qty' => 'required|numeric|min:0',
            'notes' => 'nullable'
        ]);

        try {
            DB::beginTransaction();

            $product = Product::find($request->product_id);
            $oldStock = $product->stock;
            $newStock = $oldStock - $request->qty;
            $product->update(['stock' => $newStock]);

            $pcode = str_replace(' ', '', ucwords(strtolower($product->product_name))) . "/" . str_replace(' ', '', ucwords(strtolower($product->variant)));

            // Check if an existing product with the same attributes exists
            $existingProduct = Product::where('product_name', $product->product_name)
                ->where('variant', $product->variant)
                ->where('price', $product->price)
                ->where('discount', $product->discount)
                ->where('condition', 'refurbish')
                ->first();

            $aidi = 0;

            if ($existingProduct) {
                // Update stock if product exists
                $existingProduct->stock += $request->qty;
                $existingProduct->save();
                $aidi = $existingProduct->id;

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
                    "stock" => $request->qty,
                    "status" => $request->qty > 0 ? 'Ready' : 'Out of Stock',
                    "markup" => $product->markup,
                    "product_code" => $newProductCode,
                    "type" => $product->type,
                    "condition" => 'refurbish'
                ]);

                $aidi = $newProduct->id;
            }

            RefurbishItem::create([
                'product_id' => $aidi,
                'refurbish_date' => $request->refurbish_date,
                'qty' => $request->qty,
                'notes' => $request->notes,
            ]);

            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();

            throw $e;
        }

        return redirect(route('refurbishitem-index'))->with('successCreateRefurbishItem', 'Berhasil menambahkan data rekondisi barang!');
    }

    public function edit($id){
        // return view('pages.refurbish-item.edit', [
        //     'refurbish_item' => RefurbishItem::find($id),
        //     'products' => Product::where('condition', 'degraded')->where('stock', '>', 0)->get()
        // ]);
    }

    public function update(Request $request, $id){

    }

    public function destroy($id){
        $refurbishItem = RefurbishItem::find($id);

        try{
            DB::beginTransaction();
            
            $currentRefurbishProductStock = $refurbishItem->product->stock;
            Product::find($refurbishItem->product->id)->update(['stock' => $currentRefurbishProductStock - $refurbishItem->qty]);

            $similarDegradedItem = Product::where('product_name', $refurbishItem->product->product_name)
                ->where('variant', $refurbishItem->product->variant)
                ->where('price', $refurbishItem->product->price)
                ->where('discount', $refurbishItem->product->discount)
                ->where('condition', 'degraded')
                ->first();

            if($similarDegradedItem){
                $similarDegradedItem->stock += $refurbishItem->qty;
                $similarDegradedItem->save();
            }

            $refurbishItem->delete();

            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();

            throw $e;
        }

        return redirect(route('refurbishitem-index'))->with('successDeleteRefurbishItem', 'Berhasil menghapus data rekondisi barang!');
    }
}
