<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\PurchaseProduct;

class PurchaseProductController extends Controller
{
    // List produk (cart) dari suatu purchase
    public function view_items($id)
    {
        // Targetkan purchase yang ingin ditampilkan cart-nya
        $purchase = Purchase::where("id", $id)->first();

        // Ambil data dari purchase product yang purchase_id-nya sama kayak purchase yang mau ditampilin cart-nya
        $pp = PurchaseProduct::where("purchase_id", $purchase->id)->get();

        // Tampilkan halaman pages/transit/purchaseproduct/index.blade.php beserta data yang diperlukan di blade-nya:
        return view("pages.transit.purchaseproduct.index", [
            "purchase" => $purchase, // data purchase yang mau ditampilkan cart-nya
            "pp"=> $pp // produk-produk yang terkait purchase tersebut
        ]);
    }

    // Form penambahan produk yang sudah pernah didaftarkan ke cart purchase
    public function add_existing_product($id)
    {
        // Targetkan purchase yang ingin cartnya ditambahkan product-product
        $purchase = Purchase::where("id", $id)->first();

        // Tampilkan halaman pages/transit/purchaseproduct/addpurchase.blade.php
        return view("pages.transit.purchaseproduct.addpurchase", [
            "purchase" => $purchase, // data purchase yang mau ditambahkan cart-nya
            "products" => Product::all() // semua data produk untuk dropdown/select product
        ]);
    }

    // Untuk menyimpan produk-produk ke cart purchase
    public function store_existing_product(Request $request, $id)
    {
        // Validasi data untuk mastiin tabel penambahan data ga kosong
        $request->validate([
            "products" => "required",
            "discounts" => "required",
            "quantities" => "required",
            "prices" => "required",
        ]);

        // Targetkan purchase yang mau disimpan ke cart si product-product-nya
        $purchase = Purchase::where("id", $id)->first();

        // Untuk setiap input product yang diterima lakukan:
        foreach($request->products as $index => $product_id){
            // Buat data purchase product baru di mana id purchase-nya sama kayak purchase yang mau ditambahin cart-nya dan product id sama kayak product yang mau ditambahin ke cart
            PurchaseProduct::create([
                "purchase_id" => $purchase->id,
                "product_id" => $product_id,
                "discount" => $request->discounts[$index],
                "quantity" => $request->quantities[$index],
                "price" => $request->prices[$index]
            ]);

            // Update stok dan harga product:
            $oldstock = Product::where("id", $product_id)->first()->stock; // ambil stok product yang saat ini sedang ditambahkan ke cart purchase
            Product::where("id",$product_id)->update([
                "stock" => $oldstock + $request->quantities[$index],
                "price" => $request->prices[$index]
            ]); // then update stok dan harga-nya
        };

        // Arahkan user kembali ke pages/transit/purchaseproduct/index.blade.php
        return redirect(route("purchaseproduct-viewitem", $purchase->id));
    }

    // Form penambahan produk ke cart purchase tapi produk belum terdaftar sama sekali sebelumnya
    public function add_new_product($id)
    {
        // Targetkan purchase yang cart-nya ingin ditambahkan
        $purchase = Purchase::where("id", $id)->first();

        // Tampilkan halaman pages/transit/purchaseproduct/addnewitem.blade.php beserta data yang diperlukan di blade-nya:
        return view("pages.transit.purchaseproduct.addnewitem", [
            "purchase" => $purchase, // data purchase yang ingin ditambahkan cart-nya
        ]);
    }

    // Simpan data produk (unregistered) baru ke database
    public function store_new_product(Request $request, $id)
    {
        // Validasi data, pastiin ga dikirim data kosong
        $request->validate([
            "product_name" => "required",
            "unit" => "required",
            "status" => "required",
            "variant" => "required",
            "product_code" => "required",
            "price" => "required",
            "markup" => "required",
            "stock" => "required",
        ]);

        // Targetkan purchase yang cart-nya mau ditambahin
        $purchase = Purchase::where("id", $id)->first();

        // Untuk setiap data produk yang dikirimkan lakukan:
        foreach($request->products as $index => $product_id){
            // Bikin dan tambahkan data produk ke tabel products
            $new_product = Product::create([
                "product_name" => $request->product_name,
                "unit" => $request->unit,
                "status" => $request->status,
                "variant" => $request->variant,
                "product_code" => $request->product_code,
                "price" => $request->price,
                "markup" => $request->markup,
                "stock" => $request->stock,
            ]);

            // Tambahkan data ke tabel purchase_product di mana id product sama dengan yang dibuat dan purchase sama dengan target cart purchase
            PurchaseProduct::create([
                "purchase_id" => $purchase->id,
                "product_id" => $new_product->id,
                "discount" => $request->discounts[$index],
                "quantity" => $request->quantities[$index],
                "price" => $request->prices[$index]
            ]);
        };

        // Arahkan user kembali ke halaman pages/transit/purchaseproduct/index.blade.php
        return redirect(route("purchaseproduct-viewitem", $purchase->id));
    }

    // Hapus produk dari cart
    public function destroy($id, $pid)
    {
        // Ambil semua data purchase product yang punya purchase id yang sama dengan cart purchase saat ini
        $pp = PurchaseProduct::where("id", $pid)->first();

        // Kembalikan stok produk yang ingin dihilangkan dari cart ke semula:
        $oldstock = $pp->product->stock; // Ambil stok saat ini
        Product::where("id", $pp->product->id)->update(["stock" => $oldstock - $pp->quantity]); // Kembalikan stok ke semula (dikurangi karena purchase menambah stok)

        // Hapus data purchase produk
        PurchaseProduct::destroy("id", $pp->id);

        // Arahkan kembali user ke pages/transit/purchaseproduct/index.blade.php
        return redirect(route("purchaseproduct-viewitem", $id))->with("successDeleteProduct", "Product deleted successfully!");
    }
}
