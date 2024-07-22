<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderProduct;

class DeliveryOrderController extends Controller{

    // Tabel list semua delivery order
    public function index()
    {
        // Tampilkan halaman pages/delivery-order/index.blade.php beserta data yang diperlukan di blade-nya
        return view("pages.delivery-order.index", [
            "deliveryorders" => DeliveryOrder::all() // Semua data delivery orders (buat ditampilin satu satu di tabel)
        ]);
    }

    // Form untuk buat delivery order baru (cuma delivery order-nya, kalo produk nanti harus masuk ke cart)
    public function create()
    {
        // Tampilkan halaman pages/delivery-order/create.blade.php beserta data yang diperlukan di blade-nya
        return view("pages.delivery-order.create", [
            "projects" => Project::all(), // Semua data project (buat dropdown/select project)
            "delivery_orders" => DeliveryOrder::all(), // Semua data delivery order (buat auto generate SKU)
        ]);
    }

    // Simpan data delivery order baru ke database
    public function store(Request $request)
    {
        // Validasi data, kalau ga lolos ga lanjut
        $validatedData = $request->validate([
            // "product_id" => "required",
            "delivery_date"=>"required|date",
            "project_id" => "required",
            "register" => "required|min:0|not_in:0",
            "delivery_status" => "required",
            "note"=>"nullable"
        ]);

        // Kalo data udah aman bikin dan tambahin data delivery order baru di tabel delivery_orders
        DeliveryOrder::create($validatedData);

        // Arahkan user kembali ke halaman pages/delivery-order/index.blade.php
        return redirect(route("deliveryorder-index"))->with("successAddOrder", "Order added successfully!");
    }

    // Form edit data delivery order (kalo ubah list produk harus masuk ke cart)
    public function edit($id)
    {
        // Tampilkan halaman pages/deliver-order/edit.blade.php beserta data-data yang diperlukan di blade-nya
        return view("pages.delivery-order.edit", [
            "delivery_order" => DeliveryOrder::where("id", $id)->first(), // data delivery order yang mau di-edit buat nanti auto fill di form edit
            "projects" => Project::all(), // data semua project (buat dropdown/select project)
            "status"=> ["complete", "incomplete"], // buat dropdown status delivery order
            "delivery_orders" => DeliveryOrder::all(), // data semua delivery order (buat auto generate SKU)
        ]);
    }

    // Save perubahan data delivery order ke database
    public function update(Request $request, $id)
    {
        // Validasi data, kalo ga lolos ga lanjut
        $validatedData = $request->validate([
            "delivery_date"=>"required|date",
            "project_id" => "required|min:1",
            "register" => "required|min:0|not_in:0",
            "delivery_status" => "required",
            "note"=>"nullable"
        ]);


        // Kalo semuanya aman, update data delivery order tersebut di tabel delivery_orders
        DeliveryOrder::where("id", $id)->update($validatedData);

        // Arahkan user kembali ke halaman pages/delivery-order/index.blade.php
        return redirect(route("deliveryorder-index"))->with("successEditOrder", "Order editted successfully!");
    }

    // Hapus data delivery order
    public function destroy($id)
    {
        // Kembalikan stok produk ke jumlah asalnya baru kita hapus
        // Untuk itu pertama kita perlu ambil data dari tabel delivery_order_products yang memiliki delivery order yang sama dengan yang mau dihapus (karena delivery order tidak langsung menyimpan data produk di tabelnya, relation many-to-many jadi hubungan delivery order nyimpan produk apa aja ada di tabel delivery_order_products)
        $do = DeliveryOrderProduct::where("delivery_order_id", $id)->get();

        // Untuk setiap data yang diperoleh kita lakukan:
        foreach ($do as $data){
            $product = Product::where("id", $data->product_id)->first(); // Targetkan data produk di tabel products yang aslinya (karena data $do cuma menyimpan id referensi)
            $oldstock = $product->stock; // Ambil stok saat ini
            Product::where("id", $data->product_id)->update(["stock"=> ($oldstock +
            $data->quantity)]); // Update stok product saat ini (karena sifat delivery_order mengurangi jumlah stok maka jika dikembalikan seperti semula jumlah stok product bertambah)
        }

        // Jika setiap product yang terkait sudah dikembalikan ke semula stoknya kita baru hapus data delivery order dari tabel delivery_orders
        DeliveryOrder::destroy("id", $id);

        // Arahkan user kembali ke halaman pages/delivery-order/index.blade.php
        return redirect(route("deliveryorder-index"))->with("successDeleteOrder", "Order deleted successfully!");
    }
}
