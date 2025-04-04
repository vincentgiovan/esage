<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\Storage;

class PurchaseProductController extends Controller
{
    // List produk (cart) dari suatu purchase
    public function view_items($id)
    {
        // Targetkan purchase yang ingin ditampilkan cart-nya
        $purchase = Purchase::find($id);

        // Ambil data dari purchase product yang purchase_id-nya sama kayak purchase yang mau ditampilin cart-nya
        $pp = PurchaseProduct::where("purchase_id", $purchase->id)->get();

        // Tampilkan halaman pages/transit/purchaseproduct/index.blade.php beserta data yang diperlukan di blade-nya:
        return view("pages.transit.purchaseproduct.index", [
            "purchase" => $purchase, // data purchase yang mau ditampilkan cart-nya
            "pp"=> $pp // produk-produk yang terkait purchase tersebut
        ]);
    }

    // Form penambahan produk yang sudah pernah didaftarkan ke cart purchase
    // public function add_existing_product($id)
    // {
    //     // Targetkan purchase yang ingin cartnya ditambahkan product-product
    //     $purchase = Purchase::find($id);

    //     // Tampilkan halaman pages/transit/purchaseproduct/addpurchase.blade.php
    //     return view("pages.transit.purchaseproduct.addpurchase", [
    //         "purchase" => $purchase, // data purchase yang mau ditambahkan cart-nya
    //         "products" => Product::all() // semua data produk untuk dropdown/select product
    //     ]);
    // }

    // Untuk menyimpan produk-produk ke cart purchase
    // public function store_existing_product(Request $request, $id)
    // {
    //     // Validasi data untuk mastiin tabel penambahan data ga kosong
    //     $request->validate([
    //         "products" => "required",
    //         "discounts" => "required",
    //         "quantities" => "required",
    //         "prices" => "required",
    //     ]);

    //     // Targetkan purchase yang mau disimpan ke cart si product-product-nya
    //     $purchase = Purchase::find($id);

    //     // Untuk setiap input product yang diterima lakukan:
    //     foreach($request->products as $index => $product_id){
    //         $exprod = Product::find($product_id);
    //         $clones = Product::where("product_name", $exprod->product_name)->where("variant", $exprod->variant)->orderBy("product_code")->get();
    //         $lastprod = $clones[$clones->count() - 1];

    //         // Update stok dan harga product:
    //         $old_price = $lastprod->price;
    //         $old_discount = $lastprod->discount;

    //         if($request->prices[$index] != $old_price || $request->discounts[$index] != $old_discount){
    //             $lastSlashPosition = strrpos($lastprod->product_code, '/');

    //             // Extract the part after the last '/'
    //             $lastPart = substr($lastprod->product_code, $lastSlashPosition + 1);

    //             // Extract the part before the last '/'
    //             $firstPart = substr($lastprod->product_code, 0, $lastSlashPosition + 1);

    //             $newseparatedprod = Product::create([
    //                 "product_name" => $lastprod->product_name,
    //                 "price" => $request->prices[$index],
    //                 "variant" => $lastprod->variant,
    //                 "stock" => $request->quantities[$index],
    //                 "markup" => $lastprod->markup,
    //                 "status" => $lastprod->status,
    //                 "product_code" => $firstPart . (intval($lastPart) + 1),
    //                 "unit" => $lastprod->unit,
    //                 "discount" => $request->discounts[$index],
    //                 'condition' => 'good',
    //                 'type' => $lastprod->type,
    //             ]);

    //             PurchaseProduct::create([
    //                 "purchase_id" => $purchase->id,
    //                 "product_id" => $newseparatedprod->id,
    //                 "discount" => $request->discounts[$index],
    //                 "quantity" => $request->quantities[$index],
    //                 "price" => $request->prices[$index]
    //             ]);
    //         }
    //         else {
    //             // Buat data purchase product baru di mana id purchase-nya sama kayak purchase yang mau ditambahin cart-nya dan product id sama kayak product yang mau ditambahin ke cart
    //             PurchaseProduct::create([
    //                 "purchase_id" => $purchase->id,
    //                 "product_id" => $product_id,
    //                 "discount" => $request->discounts[$index],
    //                 "quantity" => $request->quantities[$index],
    //                 "price" => $request->prices[$index]
    //             ]);

    //             $oldstock = $exprod->stock; // ambil stok product yang saat ini sedang ditambahkan ke cart purchase
    //             $newstock = $oldstock + $request->quantities[$index];
    //             $toUpdate = ["stock" => $newstock];
    //             if($newstock > 0 && $exprod->status == "Out of Stock"){
    //                 $toUpdate["status"] = "Ready";
    //             }

    //             Product::find($product_id)->update($toUpdate); // then update stok dan status-nya
    //         }
    //     };

    //     // Arahkan user kembali ke pages/transit/purchaseproduct/index.blade.php
    //     return redirect(route("purchaseproduct-viewitem", $purchase->id))->with("successAddProduct", "Berhasil menambahkan barang ke pembelian.");
    // }

    // Form penambahan produk ke cart purchase tapi produk belum terdaftar sama sekali sebelumnya
    // public function add_new_product($id)
    // {
    //     // Targetkan purchase yang cart-nya ingin ditambahkan
    //     $purchase = Purchase::find($id);

    //     // Tampilkan halaman pages/transit/purchaseproduct/addnewitem.blade.php beserta data yang diperlukan di blade-nya:
    //     return view("pages.transit.purchaseproduct.addnewitem", [
    //         "purchase" => $purchase, // data purchase yang ingin ditambahkan cart-nya
    //         "products" => Product::all()
    //     ]);
    // }

    // Simpan data produk (unregistered) baru ke database
    // public function store_new_product(Request $request, $id)
    // {
    //     // Validasi data, pastiin ga dikirim data kosong
    //     $request->validate([
    //         "product_name.*" => "required",
    //         "unit.*" => "required",
    //         "status.*" => "required",
    //         "variant.*" => "required",
    //         "product_code.*" => "required",
    //         "price.*" => "required",
    //         "markup.*" => "required",
    //         "stock.*" => "required",
    //         "discount.*" => "required",
    //         "type.*" => "required"
    //     ]);

    //     // Targetkan purchase yang cart-nya mau ditambahin
    //     $purchase = Purchase::find($id);

    //     // Untuk setiap data produk yang dikirimkan lakukan:
    //     foreach($request->product_name as $index => $product){
    //         // Bikin dan tambahkan data produk ke tabel products
    //         $new_product = Product::create([
    //             "product_name" => $product,
    //             "unit" => $request->unit[$index],
    //             "status" => $request->status[$index],
    //             "variant" => $request->variant[$index],
    //             "product_code" => $request->product_code[$index],
    //             "price" => $request->price[$index],
    //             "markup" => $request->markup[$index],
    //             "stock" => $request->stock[$index],
    //             "type" => $request->type[$index],
    //             "condition" => "good",
    //         ]);

    //         // Tambahkan data ke tabel purchase_product di mana id product sama dengan yang dibuat dan purchase sama dengan target cart purchase
    //         PurchaseProduct::create([
    //             "purchase_id" => $purchase->id,
    //             "product_id" => $new_product->id,
    //             "discount" => $request->discount[$index],
    //             "quantity" => $request->stock[$index],
    //             "price" => $request->price[$index]
    //         ]);
    //     };

    //     // Arahkan user kembali ke halaman pages/transit/purchaseproduct/index.blade.php
    //     return redirect(route("purchaseproduct-viewitem", $purchase->id))->with("successAddProduct", "Berhasil menambahkan barang ke pembelian dan menambahkannya ke data barang.");
    // }

    // Hapus produk dari cart
    public function destroy($id, $pid)
    {
        // Ambil semua data purchase product yang punya purchase id yang sama dengan cart purchase saat ini
        $pp = PurchaseProduct::find($pid);

        // Kembalikan stok produk yang ingin dihilangkan dari cart ke semula:
        $oldstock = $pp->product->stock; // Ambil stok saat ini
        $newstock = $oldstock - $pp->quantity; // Stok yang baru

        $toUpdate = ["stock" => $newstock]; // Simpen kolom data yang mau di-update by default
        if($newstock == 0){ // Kalo ternyata jumlah produk jadi 0 statusnya juga di-update
            $toUpdate["status"] = "Out of Stock";
        }

        Product::find($pp->product->id)->update($toUpdate); // Kembalikan stok ke semula (dikurangi karena purchase menambah stok)

        // Hapus data purchase produk
        PurchaseProduct::find($pp->id)->delete();

        // Arahkan kembali user ke pages/transit/purchaseproduct/index.blade.php
        return redirect(route("purchaseproduct-viewitem", $id))->with("successDeleteProduct", "Berhasil menghapus barang dari pembelian.");
    }

    // READ DATA FROM CSV
    public function import_purchaseproduct_form($id){
        return view("pages.transit.purchaseproduct.import-data", ["purchase" => Purchase::find($id)]);
    }

    public function import_purchaseproduct_store(Request $request, $id)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        // Store the file
        $file = $request->file('csv_file');
        $path = $file->store('csv_files');

        // Process the CSV file
        $this->processPurchaseProductDataCsv(storage_path('app/' . $path), $id);

        // Delete the stored file after processing
        Storage::delete($path);

        return redirect(route("purchaseproduct-viewitem", $id))->with('success', 'Berhasil membaca file CSV dan menambahkan barang ke pembelian.');
    }

    private function processPurchaseProductDataCsv($filePath, $purchase_id)
    {
        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            // Read the entire CSV file content
            $fileContent = file_get_contents($filePath);

            // Replace semicolons with commas
            // $fileContent = str_replace(';', ',', $fileContent);

            // Create a temporary file with the corrected content
            $tempFilePath = tempnam(sys_get_temp_dir(), 'csv');
            file_put_contents($tempFilePath, $fileContent);

            // Re-open the temporary file for processing
            if (($handle = fopen($tempFilePath, 'r')) !== FALSE) {
                // Skip the header row if it exists
                $header = fgetcsv($handle);

                while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                    $new_item = [
                        'product_name' => $data[0],
                        'unit' => $data[1],
                        "status" => $data[2],
                        "variant" => $data[3],
                        "product_code" => $data[4],
                        "price" => intval($data[5]),
                        "discount" => floatval(str_replace(',', '.', $data[7])),
                        "markup" => floatval(str_replace(',', '.', $data[6])),
                        "stock" => intval($data[8])
                    ];

                    $new_pp = [
                        "purchase_id" => $purchase_id,
                        "quantity" => intval($data[8]),
                    ];

                    $existing_product = Product::where("product_code", $data[4])->get();
                    if($existing_product->count()){
                        $old_stock = $existing_product->first->stock;
                        $new_item["stock"] += $old_stock;
                        Product::where("product_code", $data[4])->update($new_item);

                        $new_pp["product_id"] = $existing_product->first()->id;
                    }
                    else {
                        $new_prod = Product::create($new_item);

                        $new_pp["product_id"] = $new_prod->id;
                    }

                    PurchaseProduct::create($new_pp);
                }

                fclose($handle);
            }

            // Remove the temporary file
            unlink($tempFilePath);
        }
    }
}
