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

    // Form untuk menambahkan produk terdaftar ke suatu delivery order
    public function add_existing_product($id)
    {
        // Targetkan delivery order yang cart produknya ingin ditambahkan
        $deliveryorder = DeliveryOrder::find($id);

        // Tampilkan halaman pages/transit/deliveryorderproduct/adddeliveryorder.blade.php beserta data yang diperlukan di blade-nya
        return view("pages.transit.deliveryorderproduct.adddeliveryorder", [
            "deliveryorder" => $deliveryorder, // data delivery order yang ditargetkan
            // "products" => Product::where('products.archived', 0)
            // ->orderBy('products.product_name', 'asc') // Group by name first
            // ->orderBy('products.variant', 'asc') // Then by variant
            // ->orderByRaw("CASE WHEN products.is_returned = 1 THEN 1 ELSE 0 END") // Returned products at the bottom
            // ->get()
            "products" => Product::select('products.*', DB::raw('COALESCE(MIN(purchases.purchase_date), products.created_at) as ordering_date'))
            ->leftJoin('purchase_products', 'products.id', '=', 'purchase_products.product_id') // Join with purchase_products
            ->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id') // Join with purchases
            ->where('products.archived', 0) // Exclude archived products
            ->groupBy(
                'products.id',
                'products.product_name',
                'products.variant',
                'products.product_code',
                'products.price',
                'products.discount',
                'products.unit',
                'products.stock',
                'products.status',
                'products.markup',
                'products.is_returned',
                'products.created_at',
                'products.updated_at',
                'products.archived'
            )
            ->orderBy('products.product_name', 'asc') // Group by product name
            ->orderBy('products.variant', 'asc') // Then by variant
            ->orderByRaw("CASE WHEN products.is_returned = 'no' THEN 1 ELSE 0 END") // Place returned products at the bottom
            ->orderBy('ordering_date', 'asc') // Order by oldest purchase date or created_at
            ->get()
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
        $deliveryorder = DeliveryOrder::find($id);

        // Untuk setiap input produk yang dimasukkan, lakukan hal ini:
        foreach($request->products as $index=>$product_id){
            // Tambahkan produk sebagai bagian dari cart delivery order dengan cara tambahkan data baru di tabel delivery_order_products di mana id referensi diarahkan ke data produk dan delivery order yang sesuai
            DeliveryOrderProduct::create([
                "delivery_order_id" => $deliveryorder->id,
                "product_id" => $product_id,
                "quantity" => $request->quantities[$index],
            ]);

            // Karena delivery order sifatnya mengurangi stok produk, maka update stok product di tabel aslinya:
            $oldstock = Product::find($product_id)->stock; // Ambil stok lama produk
            $newstock = $oldstock - $request->quantities[$index]; // Stok yang baru

            $toUpdate = ["stock" => $newstock]; // Simpen kolom data yang mau di-update by default
            if($newstock == 0){ // Kalo ternyata jumlah produk jadi 0 statusnya juga di-update
                $toUpdate["status"] = "Out of Stock";
            }

            Product::find($product_id)->update($toUpdate); // Update datanya
        };

        // Arahkan user kembali ke halaman pages/transit/deliveryorderproduct/index.blade.php
        return redirect(route("deliveryorderproduct-viewitem", $deliveryorder->id))->with("successAddProduct", "Berhasil menambahkan barang ke pengiriman.");
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
