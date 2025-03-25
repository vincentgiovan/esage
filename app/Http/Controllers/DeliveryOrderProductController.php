<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\DB;
use App\Models\DeliveryOrderProduct;
use Illuminate\Support\Facades\Storage;

class DeliveryOrderProductController extends Controller
{
    // List produk yang tercatat dalam delivery order
    public function view_items($id)
    {
        // Targetkan delivery order yang dipilih yang mau dicek list produknya
        $deliveryorder = DeliveryOrder::find($id);

        // Ambil data produk yang tercatat dalam delivery order tersebut (tabel delivery_orders tidak menyimpan data produk karena relation many to many, jadi ambil data dari tabel perantara, ambil semua yang delivery_order_id-nya sama kayak delivery_order yang dipilih)
        $do = DeliveryOrderProduct::where("delivery_order_id", $deliveryorder->id)->get();

        // Kalau udah tampilkan halaman pages/transit/deliveryorderproduct/index.blade.php beserta dengan data yang diperlukan di blade-nya:
        return view("pages.transit.deliveryorderproduct.index", [
            "deliveryorder" => $deliveryorder, // list product yang tercatat di delivery order yang ingin dicek cart-nya
            "do"=> $do // data delivery order yang ingin dicek cart-nya

        ]);
    }

    // Hapus produk dari cart delivery order
    public function destroy($id, $did)
    {
        // Ambil data dari tabel delivery_order_products yang punya id produk dan delivery order yang sama dengan product yang mau dihapus dari cart delivery order yang diinginkan
        $do = DeliveryOrderProduct::find($did);

        // Kembalikan stok produk ke awal mula:
        $oldstock = $do->product->stock; // Ambil stok lama
        $newstock = $oldstock + $do->quantity;
        $toUpdate = ["stock" => $newstock];
        if($newstock > 0 && $do->product->status == "Out of Stock"){
            $toUpdate["status"] = "Ready";
        }
        Product::find($do->product->id)->update($toUpdate); // Update stok product di tabel aslinya

        // Kalau udah baru hapus data delivery order product-nya
        DeliveryOrderProduct::find($do->id)->delete();

        // Arahkan user kembali ke halaman pages/transit/deliveryorderproduct/index.blade.php
        return redirect(route("deliveryorderproduct-viewitem", $id))->with("successDeleteProduct", "Berhasil menghapus barang dari pengiriman.");
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

        return redirect(route("deliveryorderproduct-viewitem", $id))->with('success', 'Berhasil membaca file CSV dan menambahkan barang-barang ke pengiriman.');
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
                        Product::find($prod->id)->update(["stock" => $old_stock - $data[1]]);

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
