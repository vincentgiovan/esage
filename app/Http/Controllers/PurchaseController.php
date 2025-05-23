<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
    // Tabel list semua purchase
    public function index()
    {
        // Tampilkan halaman pages/purchase/index.blade.php beserta data yang diperlukan di blade-nya:
        return view("pages.purchase.index", [
            "purchases" => Purchase::filter(request(['search']))->orderBy('purchase_date', 'desc')->paginate(30) // semua data purchases buat ditampilin satu-satu
        ]);
    }

    // Form buat data purchase baru (kalo product harus tambahin di cart)
    public function create()
    {
        // Tampilkan halaman pages/purchase/create.blade.php dan data-data yang diperlukan di blade-nya:
        return view("pages.purchase.create", [
            "supplier" => Partner::all(), // data semua partner (supplier) untuk dropdown/select partner
            "purchases" => Purchase::all(), // data semua purchase untuk auto generate SKU
            "products" => Product::orderBy('product_name', 'asc')->orderBy('variant', 'asc')->get()
        ]);
    }

    // Simpan data purchase baru ke database
    public function store(Request $request)
    {
        // return $request;

        // Validasi data, ga lolos ga lanjut
        $validatedPurchaseData = $request->validate([
            "purchase_date" => "required",
            "purchase_deadline" => "required",
            "register" => "required|min:3",
            "partner_id" => "required",
            "purchase_status" => "required",
        ]);

        // $request->validate([
        //     "products" => "required",
        //     "quantities" => "required",

        //     "product_name.*" => "required",
        //     "unit.*" => "required",
        //     "status.*" => "required",
        //     "variant.*" => "required",
        //     "product_code.*" => "required",
        //     "price.*" => "required",
        //     "markup.*" => "required",
        //     "stock.*" => "required",
        //     "discount.*" => "required",
        //     "type.*" => "required"
        // ]);

        try {
            DB::beginTransaction();

            // Buat dan simpan data purchase baru ke tabel purchases
            $newPurchase = Purchase::create($validatedPurchaseData);

            // Untuk setiap produk yang sudah ada lakukan:
            if($request->products > 0){
                foreach($request->products as $index => $product_id){
                    $product = Product::find($product_id);
                    $product->stock += $request->quantities[$index];

                    PurchaseProduct::create([
                        "purchase_id" => $newPurchase->id,
                        "product_id" => $product->id,
                        "discount" => $product->discount,
                        "quantity" => $request->quantities[$index],
                        "price" => $product->price
                    ]);

                    $product->save();
                }
            }

            // Untuk setiap produk baru lakukan:
            if($request->product_name){
                $request_types = array_values($request->type);
                foreach($request->product_name as $index => $product){
                    // Bikin dan tambahkan data produk ke tabel products
                    $new_product = Product::create([
                        "product_name" => $product,
                        "unit" => $request->unit[$index],
                        "status" => 'Ready',
                        "variant" => $request->variant[$index],
                        "product_code" => $request->product_code[$index],
                        "price" => $request->price[$index],
                        "discount" => $request->discount[$index],
                        "markup" => $request->markup[$index],
                        "stock" => $request->stock[$index],
                        "type" => $request_types[$index],
                        "condition" => "good",
                    ]);

                    // Tambahkan data ke tabel purchase_product di mana id product sama dengan yang dibuat dan purchase sama dengan target cart purchase
                    PurchaseProduct::create([
                        "purchase_id" => $newPurchase->id,
                        "product_id" => $new_product->id,
                        "quantity" => $request->stock[$index],
                    ]);
                }
            }

            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();

            throw $e;
        }

        // Arahkan user kembali ke halaman pages/purchase/index.blade.php
        return redirect(route("purchase-index"))->with("successAddPurchase", "Berhasil menambahkan pembelian baru.");
    }

    // Form edit data purchase
    public function edit($id)
    {
        // Tampilkan halaman pages/purchase/edit.blade.php beserta data yang diperlukan di blade-nya:
        return view("pages.purchase.edit", [
            "purchase" => Purchase::find($id), // data purchase yang mau di-edit buat auto fill form
            "supplier" => Partner::all(), // data semua partner (supplier) buat dropdown/select partner
            "purchases" => Purchase::all(), // data semua purchase untuk auto generate SKU
            "products" => Product::orderBy('product_name', 'asc')->orderBy('variant', 'asc')->get()
        ]);
    }

    // Simpan perubahan data purchase ke database
    public function update(Request $request, $id)
    {
        // Validasi data, ga lolos ga lanjut
        $validatedData = $request->validate([
            "purchase_date" => "required",
            "purchase_deadline" => "required",
            "register" => "required|min:3",
            "partner_id" => "required",
            "purchase_status" => "required"
        ]);

        $request->validate([
            "products" => "required",
            "quantities" => "required",
        ]);

        try {
            DB::beginTransaction();

            $purchase = Purchase::find($id);

            $prev_data_list = [];

            foreach($purchase->purchase_products as $pp){
                $prev_data_list[] = [
                    'discount' => $pp->discount,
                    'price' => $pp->price,
                ];

                $product = Product::find($pp->product_id);
                $product->stock -= $pp->quantity;
                $product->save();
            }

            $purchase->purchase_products()->delete();

            foreach($request->products as $i => $reqprod){
                PurchaseProduct::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $reqprod,
                    "quantity" => $request->quantities[$i],
                ]);

                // Karena purchase sifatnya menambah stok produk, maka update stok product di tabel aslinya:
                $oldstock = Product::find($reqprod)->stock; // Ambil stok lama produk
                $newstock = $oldstock + $request->quantities[$i]; // Stok yang baru

                $toUpdate = ["stock" => $newstock]; // Simpen kolom data yang mau di-update by default
                if($newstock == 0){ // Kalo ternyata jumlah produk jadi 0 statusnya juga di-update
                    $toUpdate["status"] = "Out of Stock";
                }

                Product::find($reqprod)->update($toUpdate); // Update datanya
            }

            // Simpan perubahannya di data yang sesuai di tabel purchases
            $purchase->update($validatedData);

        DB::commit();
        }
        catch(Exception $e){
            DB::rollback();

            throw $e;
        }

        // Arahkan user kembali ke halaman pages/purchase/index.blade.php
        return redirect(route("purchase-index"))->with("successEditPurchase", "Berhasil memperbaharui data pembelian.");
    }

    // Hapus data purchase dari database
    public function destroy($id)
    {
        // Sebelumnya stok semua product yang ada di purchase harus dibalikin ke semula
        // Pertama ambil semua data di tabel purchase product yang punya purchase id yang sama kayak purchase yang mau dihapus buat dapatin semua id produk yang terkait
        $pp = PurchaseProduct::where("purchase_id", $id)->get();

        // Untuk setiap data yang kita peroleh lakukan:
        foreach ($pp as $data){
            $product = Product::find($data->product_id); // Targetkan data product di tabel aslinya
            $oldstock = $product->stock; // Ambil data stok saat ini
            Product::find($data->product_id)->update(["stock"=> ($oldstock -
            $data->quantity)]); // Kembalikan stoknya ke jumlah yang seharusnya (dikurangin karena purchase membuat stok produk bertambah)
        }

        // Hapus data purchase dari tabel purchases
        Purchase::find($id)->delete();

        // Arahkan user kembali ke halaman pages/purchase/index.blade.php
        return redirect(route("purchase-index"))->with("successDeletePurchase", "Berhasil menghapus data pengiriman.");
    }

    // READ DATA FROM CSV
    public function import_purchase_form(){
        return view("pages.purchase.import-data");
    }

    public function import_with_product_form(){
        return view("pages.purchase.import-wp");
    }

    public function import_purchase_store(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        // Store the file
        $file = $request->file('csv_file');
        $path = $file->store('csv_files');

        // Process the CSV file
        $this->processpurchaseDataCsv(storage_path('app/' . $path));

        // Delete the stored file after processing
        Storage::delete($path);

        return redirect(route("purchase-index"))->with('successImportPurchase', 'Berhasil membaca file CSV dan menambahkan data pengiriman.');
    }

    private function processpurchaseDataCsv($filePath)
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
                    $new_purchase = [
                        'purchase_date' => $data[3],
                        "purchase_deadline" => $data[4],
                        "purchase_status" => $data[5]
                    ];

                    // Insert into the products table
                    $existing_partner = Partner::where("partner_name", $data[0])->where("role", $data[1])->get();
                    if($existing_partner->count()){
                        $new_purchase["partner_id"] = $existing_partner->first()->id;
                    }
                    else {
                        $newPartner = Partner::create([
                            "role" => $data[1],
                            "partner_name" => $data[0]
                        ]);

                        $new_purchase["partner_id"] = $newPartner->id;
                    }

                    Purchase::updateOrCreate(
                        [
                            "register" => $data[2]
                        ],
                        $new_purchase
                    );
                }

                fclose($handle);
            }

            // Remove the temporary file
            unlink($tempFilePath);
        }
    }

    public function import_with_product_store(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        // Store the file
        $file = $request->file('csv_file');
        $path = $file->store('csv_files');

        // Process the CSV file
        $this->processpurchaseDataCsv2(storage_path('app/' . $path));

        // Delete the stored file after processing
        Storage::delete($path);

        return redirect(route("purchase-index"))->with('successImportPurchase', 'Berhasil membaca file CSV dan menambahkan data pengiriman beserta barang-barang di dalamnya.');
    }

    private function processpurchaseDataCsv2($filePath)
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
                    $purchase = Purchase::where("register", $data[0])->first();
                    if(!$purchase){
                        continue;
                    }

                    $targetted_product = null;
                    $product_already_exists = Product::where("product_code", $data[5])->first();
                    if(!$product_already_exists){
                        $newProduct = Product::create([
                            "product_name" => $data[1],
                            "unit" => $data[2],
                            "status" => $data[3],
                            "variant" => $data[4],
                            "product_code" => $data[5],
                            "price" => intval($data[6]),
                            "markup" => floatval($data[7]),
                            "discount" => floatval($data[8]),
                            "stock" => intval($data[9])
                        ]);

                        $targetted_product = $newProduct;
                    }
                    else {
                        $targetted_product = $product_already_exists;
                        $prevStock = $targetted_product->stock;

                        Product::find($targetted_product->id)->update(["stock" => $prevStock + intval($data[9])]);
                    }

                    PurchaseProduct::create([
                        "purchase_id" => $purchase->id,
                        "product_id" => $targetted_product->id,
                        "quantity" => intval($data[9])
                    ]);

                }

                fclose($handle);
            }

            // Remove the temporary file
            unlink($tempFilePath);
        }
    }
}
