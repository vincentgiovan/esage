<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller{
    // Tabel list semua product
    public function index()
    {
        // Tampilkan halaman pages/product/index.blade.php
        return view("pages.product.index", [
            "products" => Product::filter(request(["search"]))->paginate(5) // Data semua produk dari database buat ditampilin satu-satu (kalo user-nya searching tampilkan yang memenuhi keyword)
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
}
