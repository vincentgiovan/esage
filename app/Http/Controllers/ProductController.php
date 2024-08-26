<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller{
    // Tabel list semua product
    public function index()
    {
        $n_pagination = 10;
        // Tampilkan halaman pages/product/index.blade.php
        return view("pages.product.index", [
            "products" => Product::filter(request(["search"]))->paginate($n_pagination), // Data semua produk dari database buat ditampilin satu-satu (kalo user-nya searching tampilkan yang memenuhi keyword)
            "n_pagination" => $n_pagination
        ]);
    }

    // Form registrasi produk baru
    public function create()
    {
        // Tampilkan halaman pages/product/create.blade.php
        return view("pages.product.create");
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

            // Replace semicolons with commas
            $fileContent = str_replace(';', ',', $fileContent);

            // Create a temporary file with the corrected content
            $tempFilePath = tempnam(sys_get_temp_dir(), 'csv');
            file_put_contents($tempFilePath, $fileContent);

            // Re-open the temporary file for processing
            if (($handle = fopen($tempFilePath, 'r')) !== FALSE) {
                // Skip the header row if it exists
                $header = fgetcsv($handle);

                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
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
                            "markup" => floatval(str_replace(',', '.', $data[6])),
                            "stock" => intval($data[7])
                        ]
                    );
                }

                fclose($handle);
            }

            // Remove the temporary file
            unlink($tempFilePath);
        }
    }

}
