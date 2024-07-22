<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Partner;
use App\Models\Purchase;
use App\Models\Pembelian;
use App\Models\Product;
use App\Models\PurchaseProduct;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    // Tabel list semua purchase
    public function index()
    {
        // Tampilkan halaman pages/purchase/index.blade.php beserta data yang diperlukan di blade-nya:
        return view("pages.purchase.index", [
            "purchases" => Purchase::all() // semua data purchases buat ditampilin satu-satu
        ]);
    }

    // Form buat data purchase baru (kalo product harus tambahin di cart)
    public function create()
    {
        // Tampilkan halaman pages/purchase/create.blade.php dan data-data yang diperlukan di blade-nya:
        return view("pages.purchase.create", [
            "supplier" => Partner::all(), // data semua partner (supplier) untuk dropdown/select partner
            "status" => ["Complete", "Incomplete"], // untuk dropdown status purchase
            "purchases" => Purchase::all() // data semua purchase untuk auto generate SKU
        ]);
    }

    // Simpan data purchase baru ke database
    public function store(Request $request)
    {
        // Validasi data, ga lolos ga lanjut
        $validatedData = $request->validate([
            "purchase_date" => "required",
            "purchase_deadline" => "required",
            "register" => "required|min:3",
            "partner_id" => "required",
            "purchase_status" => "required"
        ]);

        // Buat dan simpan data purchase baru ke tabel purchases
        Purchase::create($validatedData);

        // Arahkan user kembali ke halaman pages/purchase/index.blade.php
        return redirect(route("purchase-index"))->with("successAddPurchase", "Purchase added successfully!");
    }

    // Form edit data purchase
    public function edit($id)
    {
        // Tampilkan halaman pages/purchase/edit.blade.php beserta data yang diperlukan di blade-nya:
        return view("pages.purchase.edit", [
            "purchase" => Purchase::where("id", $id)->first(), // data purchase yang mau di-edit buat auto fill form
            "supplier" => Partner::all(), // data semua partner (supplier) buat dropdown/select partner
            "status" => ["Complete", "Incomplete"], // untuk dropdown status purchase
            "purchases" => Purchase::all() // data semua purchase untuk auto generate SKU
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

        // Simpan perubahannya di data yang sesuai di tabel purchases
        Purchase::where("id", $id)->update($validatedData);

        // Arahkan user kembali ke halaman pages/purchase/index.blade.php
        return redirect(route("purchase-index"))->with("successEditProduct", "Product editted successfully!");
    }

    // Hapus data purchase dari database
    public function destroy($id)
    {
        // Sebelumnya stok semua product yang ada di purchase harus dibalikin ke semula
        // Pertama ambil semua data di tabel purchase product yang punya purchase id yang sama kayak purchase yang mau dihapus buat dapatin semua id produk yang terkait
        $pp = PurchaseProduct::where("purchase_id", $id)->get();

        // Untuk setiap data yang kita peroleh lakukan:
        foreach ($pp as $data){
            $product = Product::where("id", $data->product_id)->first(); // Targetkan data product di tabel aslinya
            $oldstock = $product->stock; // Ambil data stok saat ini
            Product::where("id", $data->product_id)->update(["stock"=> ($oldstock -
            $data->quantity)]); // Kembalikan stoknya ke jumlah yang seharusnya (dikurangin karena purchase membuat stok produk bertambah)
        }

        // Hapus data purchase dari tabel purchases
        Purchase::destroy("id", $id);

        // Arahkan user kembali ke halaman pages/purchase/index.blade.php
        return redirect(route("purchase-index"))->with("successDeleteProduct", "Product deleted successfully!");
    }
}
