<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;


class ProductController extends Controller{
    // Tabel list semua product
    public function index()
    {
        $n_pagination = 10;
        $products = Product::filter(request(["search"]))->orderByRaw('CASE WHEN status = "Out of Stock" THEN 0 ELSE 1 END')->orderBy("product_name")->get(); // Data semua produk dari database buat ditampilin satu-satu (kalo user-nya searching tampilkan yang memenuhi keyword)

        $grouped_products = $products->groupBy(function ($product) {
            return "{$product->product_name}|{$product->variant}|{$product->is_returned}";
        })->map(function ($group) {
            // Use the first product in the group as a base for the grouped product
            $baseProduct = $group->first()->replicate();

            // Aggregate stock and update fields
            $baseProduct->stock = $group->sum('stock'); // Total stock
            $baseProduct->price = $group->last()->price; // Latest price
            $baseProduct->is_grouped = true; // Custom marker for grouped entry

            // Return the grouped product along with the original group
            return [
                'grouped' => $baseProduct,
                'originals' => $group
            ];
        })->flatMap(function ($item) {
            // Flatten each group to include the grouped product followed by originals
            return $item['originals']->prepend($item['grouped']);
        })->values(); // Reset the keys for a clean indexed collection

        // Tampilkan halaman pages/product/index.blade.php
        return view("pages.product.index", [
            "products" => $grouped_products,
            "n_pagination" => $n_pagination
        ]);
    }

    // Form registrasi produk baru
    public function create()
    {
        // Tampilkan halaman pages/product/create.blade.php
        return view("pages.product.create", [
            "products" => Product::where('archived', 0)->get()
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
        return redirect(route("product-index"))->with("successAddProduct", "Berhasil menambahkan barang baru.");
    }

    // Form edit data produk
    public function edit($id)
    {
        // Tampilkan halaman pages/product/edit.blade.php beserta data yang diperlukan di blade-nya
        return view("pages.product.edit", [
            "product" => Product::find($id), // data product yang mau di-edit buat auto fill form-nya
            "status" => ["Ready", "Out Of Stock"] // buat dropdown status product
        ]);
    }

    // Simpan perubahan data produk ke database
    public function update(Request $request, $id)
    {
        // Gudang bisa update stock
        if(Auth::user()->role->role_name == 'gudang'){
            $validatedData = $request->validate([
                "stock" => "required|numeric|min:0",
                "status" => "required|min:3",
            ]);
        }

        // Product manager bisa update markup
        else if(Auth::user()->role->role_name == 'purchasing_admin'){
            $validatedData = $request->validate([
                "markup" => "required|numeric|min:0",
            ]);
        }

        // Role yang lain
        else {
            $validatedData = $request->validate([
                "product_name" => "required|min:3",
                "price"=>"required|numeric|min:0|not_in:0",
                "variant" => "required|min:3",
                "stock" => "required|numeric|min:0",
                "markup" => "required|numeric|min:0",
                "status" => "required|min:3",
                "product_code" => "required|min:3",
                "unit"=>"required"
            ]);
        }

        // Simpan perubahan datanya di data produk yang ditargetkan di tabel products
        Product::find($id)->update($validatedData);

        // Arahkan user kembali ke halaman pages/product/index.blade.php
        return redirect(route("product-index"))->with("successEditProduct", "Berhasil memperbaharui data barang.");
    }

    // Hapus data product dari database
    public function destroy($id)
    {
        // Hapus data product yang ditargetkan dari tabel products
        Product::find($id)->update(["archived" => 1]);

        // Arahkan user kembali ke halaman pages/product/index.blade.php
        return redirect(route("product-index"))->with("successDeleteProduct", "Berhasil menghapus data barang.");
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

        return redirect(route("product-index"))->with('success', 'Berhasil membaca file CSV dan menambahkan data barang.');
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

    // EXPORT EXCEL
    public function export_excel()
    {
        return Excel::download(new ProductsExport, 'testus.xlsx');
    }

    public function view_log($id){
        $product = Product::find($id);

        $similars = Product::where("product_name", $product->product_name)->where("variant", $product->variant)->where('price', $product->price)->where('discount', $product->discount)->get();

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
