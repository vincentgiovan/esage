<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\DeliveryOrder;
use Illuminate\Http\Request;
use App\Models\DeliveryOrderProduct;

class DeliveryOrderProductController extends Controller
{
    // List produk yang tercatat dalam delivery order
    public function view_items($id)
    {
        // Targetkan delivery order yang dipilih yang mau dicek list produknya
        $deliveryorder = DeliveryOrder::where("id", $id)->first();

        // Ambil data produk yang tercatat dalam delivery order tersebut (tabel delivery_orders tidak menyimpan data produk karena relation many to many, jadi ambil data dari tabel perantara, ambil semua yang delivery_order_id-nya sama kayak delivery_order yang dipilih)
        $do = DeliveryOrderProduct::where("delivery_order_id", $deliveryorder->id)->get();

        // Kalau udah tampilkan halaman pages/transit/deliveryorderproduct/index.blade.php beserta dengan data yang diperlukan di blade-nya:
        return view("pages.transit.deliveryorderproduct.index", [
            "deliveryorder" => $deliveryorder, // list product yang tercatat di delivery order yang ingin dicek cart-nya
            "do"=> $do // data delivery order yang ingin dicek cart-nya

        ]);
    }

    // Form untuk menambahkan produk terdaftar ke suatu delivery order
    public function add_existing_product($id)
    {
        // Targetkan delivery order yang cart produknya ingin ditambahkan
        $deliveryorder = DeliveryOrder::where("id", $id)->first();

        // Tampilkan halaman pages/transit/deliveryorderproduct/adddeliveryorder.blade.php beserta data yang diperlukan di blade-nya
        return view("pages.transit.deliveryorderproduct.adddeliveryorder", [
            "deliveryorder" => $deliveryorder, // data delivery order yang ditargetkan
            "products" => Product::all() // list semua produk terdaftar untuk dropdown/select produk
        ]);
    }

    // Simpan list produk (produk yang sudah pernah didaftarkan sebelumnya) ke dalam database
    public function store_existing_product(Request $request, $id)
    {
        // Validasi data (memastikan tidak kosong), kalau aman lanjut
        $request->validate([
            "products" => "required",
            "quantities" => "required",
        ]);

        // Targetkan delivery order yang cart produknya ingin ditambahkan
        $deliveryorder = DeliveryOrder::where("id",$id)->first();

        // Untuk setiap input produk yang dimasukkan, lakukan hal ini:
        foreach($request->products as $index=>$product_id){
            // Tambahkan produk sebagai bagian dari cart delivery order dengan cara tambahkan data baru di tabel delivery_order_products di mana id referensi diarahkan ke data produk dan delivery order yang sesuai
            DeliveryOrderProduct::create([
                "delivery_order_id" => $deliveryorder->id,
                "product_id" => $product_id,
                "quantity" => $request->quantities[$index],
            ]);

            // Karena delivery order sifatnya mengurangi stok produk, maka update stok product di tabel aslinya:
            $oldstock = Product::where("id",$product_id)->first()->stock; // Ambil stok lama produk
            Product::where("id",$product_id)->update(["stock" => $oldstock - $request->quantities[$index]]); // Update datanya
        };

        // Arahkan user kembali ke halaman pages/transit/deliveryorderproduct/index.blade.php
        return redirect(route("deliveryorderproduct-viewitem", $deliveryorder->id));
    }

    // Hapus produk dari cart delivery order
    public function destroy($id, $did)
    {
        // Ambil data dari tabel delivery_order_products yang punya id produk dan delivery order yang sama dengan product yang mau dihapus dari cart delivery order yang diinginkan
        $do = DeliveryOrderProduct::where("id", $did)->first();

        // Kembalikan stok produk ke awal mula:
        $oldstock = $do->product->stock; // Ambil stok lama
        Product::where("id", $do->product->id)->update(["stock" => $oldstock + $do->quantity]); // Update stok product di tabel aslinya

        // Kalau udah baru hapus data delivery order product-nya
        DeliveryOrderProduct::destroy("id", $do->id);

        // Arahkan user kembali ke halaman pages/transit/deliveryorderproduct/index.blade.php
        return redirect(route("deliveryorderproduct-viewitem", $id))->with("successDeleteProduct", "Product deleted successfully!");
    }
}
