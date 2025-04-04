<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;



class ProductController extends Controller{
    // Tabel list semua product
    public function index(Request $request)
    {
        $products = Product::filter(request(["condition", "search"]))->orderByRaw('CASE WHEN status = "Out of Stock" THEN 0 ELSE 1 END')->orderBy("product_name")->get();

        $grouped_products = $products->groupBy(function ($product) {
            return "{$product->product_name}|{$product->variant}";
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
        });

        // ->flatMap(function ($item) {
        //     // Flatten each group to include the grouped product followed by originals
        //     return $item['originals']->prepend($item['grouped']);
        // })->values(); // Reset the keys for a clean indexed collection

        // Paginate the grouped collection
        // $page = $request->input('page', 1);
        // $perPage = 30; // Adjust the number of items per page
        // $offset = ($page - 1) * $perPage;

        // $paginatedProducts = new LengthAwarePaginator(
        //     $grouped_products->slice($offset, $perPage)->values(), // Items for current page
        //     $grouped_products->count(), // Total items
        //     $perPage, // Items per page
        //     $page, // Current page
        //     ['path' => $request->url(), 'query' => $request->query()] // Maintain query parameters
        // );

        // Convert the collection of groups into a paginatable format
        $groupedArray = $grouped_products->values(); // Reset keys

        // PAGINATE BEFORE FLATTENING
        $page = $request->input('page', 1);
        $perPage = 30; // Paginate by groups, not individual items
        $totalGroups = $groupedArray->count();
        $offset = ($page - 1) * $perPage;

        // Slice only full groups (preserves grouped integrity)
        $paginatedGroups = $groupedArray->slice($offset, $perPage);

        // Flatten only AFTER paginating to ensure grouped items stay together
        $flattenedResults = $paginatedGroups->flatMap(function ($item) {
            return $item['originals']->prepend($item['grouped']);
        })->values();

        // Create paginator with the correctly paginated dataset
        $paginatedProducts = new LengthAwarePaginator(
            $flattenedResults, // Only the sliced groups
            $totalGroups, // Total groups count
            $perPage, // Items per page (by groups)
            $page, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // Maintain query parameters
        );

        // Tampilkan halaman pages/product/index.blade.php
        return view("pages.product.index", [
            "products" => $paginatedProducts,
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
        // Gudang, subgudang, project manager bisa update stok doang
        if(in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager'])){
            $validatedData = $request->validate([
                "product_name" => "required|min:3",
                "price" => "required|numeric|min:0|not_in:0",
                "variant" => "required|min:3",
                "stock" => "required|numeric|min:0",
                "status" => "required|min:3",
                "product_code" => "required|min:3",
                "unit" => "required",
                'condition' => 'required',
                'type' => 'required',
            ]);
        }

        else {
            // Validasi data, kalau ga lolos ga lanjut
            $validatedData = $request->validate([
                "product_name" => "required|min:3",
                "price" => "required|numeric|min:0|not_in:0",
                "variant" => "required|min:3",
                "stock" => "required|numeric|min:0",
                "markup" => "required|numeric|min:0",
                "status" => "required|min:3",
                "product_code" => "required|min:3",
                "unit" => "required",
                'condition' => 'required',
                'type' => 'required',
                'discount' => 'required|numeric|min:0'
            ]);
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
        // Gudang, subgudang, project manager bisa update stok doang
        if(in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager'])){
            $validatedData = $request->validate([
                "stock" => "required|numeric|min:0",
                "status" => "required|min:3",
            ]);
        }

        // Purchasing admin cuma markup ama diskon
        else if(Auth::user()->role->role_name == 'purchasing_admin'){
            $validatedData = $request->validate([
                "markup" => "required|numeric|min:0",
                'discount' => "required|numeric|min:0",
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
                "unit"=>"required",
                'type' => 'required'
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
        Product::find($id)->delete();

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
            'file_to_upload' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $temp_path = $request->file('file_to_upload')->store('temp');

        try {
			Excel::import(new ProductsImport, $temp_path);

            Storage::delete($temp_path);

			return redirect(route("product-index"))->with('successImportExcel', 'Berhasil membaca file Excel dan menambahkan data barang.');
		}

		catch (Exception $e){
            Storage::delete($temp_path);

            throw $e;

			// return back()->with('failedImportExcel', "Gagal membaca dan menambahkan produk dari file Excel, harap perhatikan format yang telah ditentukan dan silakan coba kembali.");
		}
    }

    // EXPORT EXCEL
    public function export_excel()
    {
        return Excel::download(new ProductsExport, 'data-produk.xlsx');
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
