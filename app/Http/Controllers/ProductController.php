<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\PurchaseProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller{
    // Tabel list semua product
    public function index()
    {
        $n_pagination = 10;
        $products = Product::filter(request(["search"]))->orderByRaw('CASE WHEN status = "Out of Stock" THEN 0 ELSE 1 END')->orderBy("product_name")->get(); // Data semua produk dari database buat ditampilin satu-satu (kalo user-nya searching tampilkan yang memenuhi keyword)

        $all_products = [];
        foreach($products as $product){
            $pae = false;
            foreach($all_products as $ap){
                if($product->variant == $ap->variant && $product->product_name == $ap->product_name){
                    $ap["stock"] += $product->stock;
                    $ap["price"] = $product->price;
                    $pae = true;
                }
            }

            if(!$pae){
                array_push($all_products, $product);
            }
        }

        // Tampilkan halaman pages/product/index.blade.php
        return view("pages.product.index", [
            "products" => collect($all_products),
            "n_pagination" => $n_pagination
        ]);
    }

    // Form registrasi produk baru
    public function create()
    {
        // Tampilkan halaman pages/product/create.blade.php
        return view("pages.product.create", [
            "products" => Product::all()
        ]);
    }

    // Simpan data produk baru ke database
    public function store(Request $request)
    {
        // Validasi data, kalau ga lolos ga lanjut
        $validatedData = $request->validate([
            "product_name" => "required|min:3",
            "price" => "required|numeric|min:0|not_in:0",
            "variant" => "required|min:3",
            "stock" => "required|numeric|min:0",
            "markup" => "nullable|numeric",
            "status" => "required|min:3",
            "product_code" => "required|min:3",
            "unit" => "required"
        ]);

        if(!$validatedData["markup"]){
            $validatedData["markup"] = 0;
        }

        // Bikin dan simpan data produk baru di tabel products
        Product::create($validatedData);

        // Arahkan user kembali ke halaman pages/product/index.blade.php
        return redirect(route("product-index"))->with("successAddProduct", "Product added successfully!");
    }

    // Form edit data produk
    public function edit($id)
    {
        // Tampilkan halaman pages/product/edit.blade.php beserta data yang diperlukan di blade-nya
        return view("pages.product.edit", [
            "product" => Product::where("id", $id)->first(), // data product yang mau di-edit buat auto fill form-nya
            "status" => ["Ready", "Out Of Stock"] // buat dropdown status product
        ]);
    }

    // Simpan perubahan data produk ke database
    public function update(Request $request, $id)
    {
        // Validasi data, kalo ga lolos ga lanjut
        $validatedData = $request->validate([
            "product_name" => "required|min:3",
            "price"=>"required|numeric|min:0|not_in:0",
            "variant" => "required|min:3",
            "stock" => "required|numeric|min:0|not_in:0",
            "markup" => "nullable|numeric",
            "status" => "required|min:3",
            "product_code" => "required|min:3",
            "unit"=>"required"
        ]);

        // Simpan perubahan datanya di data produk yang ditargetkan di tabel products
        Product::where("id", $id)->update($validatedData);

        // Arahkan user kembali ke halaman pages/product/index.blade.php
        return redirect(route("product-index"))->with("successEditProduct", "Product editted successfully!");
    }

    // Hapus data product dari database
    public function destroy($id)
    {
        // Hapus data product yang ditargetkan dari tabel products
        Product::destroy("id", $id);

        // Arahkan user kembali ke halaman pages/product/index.blade.php
        return redirect(route("product-index"))->with("successDeleteProduct", "Product deleted successfully!");
    }


    // READ DATA FROM CSV
    public function import_product_form(){
        return view("pages.product.import-data");
    }

    public function import_product_store(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        // Store the file
        $file = $request->file('csv_file');
        $path = $file->store('csv_files');

        // Process the CSV file
        $this->processProductDataCsv(storage_path('app/' . $path));

        // Delete the stored file after processing
        Storage::delete($path);

        return redirect(route("product-index"))->with('success', 'CSV file uploaded and products added successfully.');
    }

    private function processProductDataCsv($filePath)
    {
        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            // Read the entire CSV file content
            $fileContent = file_get_contents($filePath);

            // // Replace semicolons with commas
            // $fileContent = str_replace(';', ',', $fileContent);

            // Create a temporary file with the corrected content
            $tempFilePath = tempnam(sys_get_temp_dir(), 'csv');
            file_put_contents($tempFilePath, $fileContent);

            // Re-open the temporary file for processing
            if (($handle = fopen($tempFilePath, 'r')) !== FALSE) {
                // Skip the header row if it exists
                $header = fgetcsv($handle);

                while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                    // Insert into the products table
                    Product::updateOrCreate(
                        [
                            "product_code" => $data[4],
                        ],
                        [
                            'product_name' => $data[0],
                            'unit' => $data[1],
                            "status" => $data[2],
                            "variant" => $data[3],
                            "price" => intval($data[5]),
                            "discount" => floatval(str_replace(',', '.', $data[6])),
                            "markup" => floatval(str_replace(',', '.', $data[7])),
                            "stock" => intval($data[8])
                        ]
                    );
                }

                fclose($handle);
            }

            // Remove the temporary file
            unlink($tempFilePath);
        }
    }

    public function view_log($id){
        $product = Product::find($id);

        $similars = Product::where("product_name", $product->product_name)->where("variant", $product->variant)->get();

        $purchaseproducts = [];
        foreach($similars as $s){
            $pp = PurchaseProduct::where("product_id", $s->id)->get();
            foreach($pp as $p){
                array_push($purchaseproducts, $p);
            }
        }

        return view("pages.product.log", ["purchaseproducts" => collect($purchaseproducts)]);
    }
}
