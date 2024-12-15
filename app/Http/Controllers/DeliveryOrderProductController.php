<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderProduct;
use Illuminate\Support\Facades\Storage;

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
            $newstock = $oldstock - $request->quantities[$index]; // Stok yang baru

            $toUpdate = ["stock" => $newstock]; // Simpen kolom data yang mau di-update by default
            if($newstock == 0){ // Kalo ternyata jumlah produk jadi 0 statusnya juga di-update
                $toUpdate["status"] = "Out of Stock";
            }

            Product::where("id",$product_id)->update($toUpdate); // Update datanya
        };

        // Arahkan user kembali ke halaman pages/transit/deliveryorderproduct/index.blade.php
        return redirect(route("deliveryorderproduct-viewitem", $deliveryorder->id))->with("successAddProduct", "Product Added successfully!");
    }

    // Hapus produk dari cart delivery order
    public function destroy($id, $did)
    {
        // Ambil data dari tabel delivery_order_products yang punya id produk dan delivery order yang sama dengan product yang mau dihapus dari cart delivery order yang diinginkan
        $do = DeliveryOrderProduct::where("id", $did)->first();

        // Kembalikan stok produk ke awal mula:
        $oldstock = $do->product->stock; // Ambil stok lama
        $newstock = $oldstock + $do->quantity;
        $toUpdate = ["stock" => $newstock];
        if($newstock > 0 && $do->product->status == "Out of Stock"){
            $toUpdate["status"] = "Ready";
        }
        Product::where("id", $do->product->id)->update($toUpdate); // Update stok product di tabel aslinya

        // Kalau udah baru hapus data delivery order product-nya
        DeliveryOrderProduct::find($do->id)->delete();

        // Arahkan user kembali ke halaman pages/transit/deliveryorderproduct/index.blade.php
        return redirect(route("deliveryorderproduct-viewitem", $id))->with("successDeleteProduct", "Product deleted successfully!");
    }

    // READ DATA FROM CSV
    public function import_deliveryorderproduct_form($id){
        return view("pages.transit.deliveryorderproduct.import-data", ["deliveryorder" => DeliveryOrder::find($id)]);
    }

    public function import_deliveryorderproduct_store(Request $request, $id)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        // Store the file
        $file = $request->file('csv_file');
        $path = $file->store('csv_files');

        // Process the CSV file
        $this->processDeliveryOrderProductDataCsv(storage_path('app/' . $path), $id);

        // Delete the stored file after processing
        Storage::delete($path);

        return redirect(route("deliveryorderproduct-viewitem", $id))->with('success', 'CSV file uploaded and products added successfully.');
    }

    private function processDeliveryOrderProductDataCsv($filePath, $delivery_order_id)
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
                    $product = Product::where("product_code", $data[0])->get();
                    if($product->count()){
                        $prod = $product->first();
                        $old_stock = $prod->stock;
                        Product::where("id", $prod->id)->update(["stock" => $old_stock - $data[1]]);

                        DeliveryOrderProduct::create([
                            "product_id" => $product->first()->id,
                            "delivery_order_id" => $delivery_order_id,
                            "quantity" => $data[1]
                        ]);
                    }

                }

                fclose($handle);
            }

            // Remove the temporary file
            unlink($tempFilePath);
        }
    }
}
