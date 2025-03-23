<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use Illuminate\Support\Facades\DB;
use App\Models\DeliveryOrderProduct;
use Exception;
use Illuminate\Support\Facades\Storage;

class DeliveryOrderController extends Controller{

    // Tabel list semua delivery order
    public function index()
    {
        // Tampilkan halaman pages/delivery-order/index.blade.php beserta data yang diperlukan di blade-nya
        return view("pages.delivery-order.index", [
            "deliveryorders" => DeliveryOrder::filter(request(['search']))->orderBy('delivery_date', 'desc')->get() // Semua data delivery orders (buat ditampilin satu satu di tabel)
        ]);
    }

    // Form untuk buat delivery order baru (cuma delivery order-nya, kalo produk nanti harus masuk ke cart)
    public function create()
    {
        // Tampilkan halaman pages/delivery-order/create.blade.php beserta data yang diperlukan di blade-nya
        return view("pages.delivery-order.create", [
            "projects" => Project::all(), // Semua data project (buat dropdown/select project)
            "delivery_orders" => DeliveryOrder::all(), // Semua data delivery order (buat auto generate SKU)
            "products" => Product::select('products.*', DB::raw('COALESCE(MIN(purchases.purchase_date), products.created_at) as ordering_date'))
            ->leftJoin('purchase_products', 'products.id', '=', 'purchase_products.product_id') // Join with purchase_products
            ->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id') // Join with purchases
            ->where('stock', '!=', 0)
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
                'products.condition',
                'products.type',
                'products.created_at',
                'products.updated_at',
            )
            ->orderBy('products.product_name', 'asc') // Group by product name
            ->orderBy('products.variant', 'asc') // Then by variant
            ->orderBy('ordering_date', 'asc') // Order by oldest purchase date or created_at
            ->get()

        ]);
    }

    // Simpan data delivery order baru ke database
    public function store(Request $request)
    {
        // return $request;

        // Validasi data, kalau ga lolos ga lanjut
        $request->validate([
            "delivery_date"=>"required|date",
            "project_id" => "required",
            "register" => "required",
            "delivery_status" => "required",
            "note" => "nullable",
            "products.*" => "required",
            "quantities.*" => "required",
        ]);

        try {
            DB::beginTransaction();

            // Kalo data udah aman bikin dan tambahin data delivery order baru di tabel delivery_orders
            $newDevor = DeliveryOrder::create([
                'delivery_date' => $request->delivery_date,
                'project_id' => $request->project_id,
                'register' => $request->register,
                'delivery_status' => $request->delivery_status,
                'note' => $request->note
            ]);

            // Untuk setiap input produk yang dimasukkan, lakukan hal ini:
            foreach($request->products as $index => $product_id){
                // Tambahkan produk sebagai bagian dari cart delivery order dengan cara tambahkan data baru di tabel delivery_order_products di mana id referensi diarahkan ke data produk dan delivery order yang sesuai
                DeliveryOrderProduct::create([
                    "delivery_order_id" => $newDevor->id,
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

            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();

            throw $e;
        }

        // Arahkan user kembali ke halaman pages/delivery-order/index.blade.php
        return redirect(route("deliveryorder-index"))->with("successAddOrder", "Berhasil menambahkan pengiriman baru.");
    }

    // Form edit data delivery order (kalo ubah list produk harus masuk ke cart)
    public function edit($id)
    {
        // Tampilkan halaman pages/deliver-order/edit.blade.php beserta data-data yang diperlukan di blade-nya
        return view("pages.delivery-order.edit", [
            "delivery_order" => DeliveryOrder::find($id), // data delivery order yang mau di-edit buat nanti auto fill di form edit
            "projects" => Project::all(), // data semua project (buat dropdown/select project)
            "status"=> ["complete", "incomplete"], // buat dropdown status delivery order
            "delivery_orders" => DeliveryOrder::all(), // data semua delivery order (buat auto generate SKU)
            "products" => Product::select('products.*', DB::raw('COALESCE(MIN(purchases.purchase_date), products.created_at) as ordering_date'))
            ->leftJoin('purchase_products', 'products.id', '=', 'purchase_products.product_id') // Join with purchase_products
            ->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id') // Join with purchases
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
                'products.condition',
                'products.type',
                'products.created_at',
                'products.updated_at',
            )
            ->orderBy('products.product_name', 'asc') // Group by product name
            ->orderBy('products.variant', 'asc') // Then by variant
            ->orderBy('ordering_date', 'asc') // Order by oldest purchase date or created_at
            ->get()
        ]);
    }

    // Save perubahan data delivery order ke database
    public function update(Request $request, $id)
    {
        // Validasi data, kalo ga lolos ga lanjut
        $request->validate([
            "delivery_date"=>"required|date",
            "project_id" => "required",
            "register" => "required",
            "delivery_status" => "required",
            "note" => "nullable",
            "products.*" => "required",
            "quantities.*" => "required",
        ]);

        try {
            DB::beginTransaction();

            $devor = DeliveryOrder::find($id);

            // Revert stock back
            foreach($devor->delivery_order_products as $dop){
                $product = Product::find($dop->product_id);
                $product->stock += $dop->quantity;
                $product->save();
            }

            $devor->delivery_order_products()->delete();

            // Kalo semuanya aman, update data delivery order tersebut di tabel delivery_orders
            $devor->update([
                'delivery_date' => $request->delivery_date,
                'project_id' => $request->project_id,
                'register' => $request->register,
                'delivery_status' => $request->delivery_status,
                'note' => $request->note
            ]);

            // Untuk setiap input produk yang dimasukkan, lakukan hal ini:
            foreach($request->products as $index => $product_id){
                // Tambahkan produk sebagai bagian dari cart delivery order dengan cara tambahkan data baru di tabel delivery_order_products di mana id referensi diarahkan ke data produk dan delivery order yang sesuai
                DeliveryOrderProduct::create([
                    "delivery_order_id" => $devor->id,
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

            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();

            throw $e;
        }

        // Arahkan user kembali ke halaman pages/delivery-order/index.blade.php
        return redirect(route("deliveryorder-index"))->with("successEditOrder", "Berhasil memperbaharui data pengiriman.");
    }

    // Hapus data delivery order
    public function destroy($id)
    {
        // Kembalikan stok produk ke jumlah asalnya baru kita hapus
        // Untuk itu pertama kita perlu ambil data dari tabel delivery_order_products yang memiliki delivery order yang sama dengan yang mau dihapus (karena delivery order tidak langsung menyimpan data produk di tabelnya, relation many-to-many jadi hubungan delivery order nyimpan produk apa aja ada di tabel delivery_order_products)
        $do = DeliveryOrderProduct::where("delivery_order_id", $id)->get();

        // Untuk setiap data yang diperoleh kita lakukan:
        foreach ($do as $data){
            $product = Product::find($data->product_id); // Targetkan data produk di tabel products yang aslinya (karena data $do cuma menyimpan id referensi)
            $oldstock = $product->stock; // Ambil stok saat ini
            Product::find($data->product_id)->update(["stock"=> ($oldstock +
            $data->quantity)]); // Update stok product saat ini (karena sifat delivery_order mengurangi jumlah stok maka jika dikembalikan seperti semula jumlah stok product bertambah)
        }

        // Jika setiap product yang terkait sudah dikembalikan ke semula stoknya kita baru hapus data delivery order dari tabel delivery_orders
        DeliveryOrder::find($id)->delete();

        // Arahkan user kembali ke halaman pages/delivery-order/index.blade.php
        return redirect(route("deliveryorder-index"))->with("successDeleteOrder", "Berhasil menghapus data pengiriman.");
    }

    // READ DATA FROM CSV
    public function import_deliveryorder_form(){
        return view("pages.delivery-order.import-data");
    }

    public function import_with_product_form(){
        return view("pages.delivery-order.import-wp");
    }

    public function import_deliveryorder_store(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        // Store the file
        $file = $request->file('csv_file');
        $path = $file->store('csv_files');

        // Process the CSV file
        $this->processdeliveryorderDataCsv(storage_path('app/' . $path));

        // Delete the stored file after processing
        Storage::delete($path);

        return redirect(route("deliveryorder-index"))->with('successImportDevor', 'Berhasil membaca file CSV dan menambahkan data pengiriman.');
    }

    private function processdeliveryorderDataCsv($filePath)
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
                    $new_delivery_order = [
                        "delivery_date" => $data[1],
                        "delivery_status" => $data[2],
                    ];

                    // Insert into the products table
                    $existing_project = Project::where("project_name", $data[3])->get();
                    if($existing_project->count()){
                        $new_delivery_order["project_id"] = $existing_project->first()->id;
                    }
                    else {
                        $newProject = Project::create([
                            "project_name" => $data[3],
                            "location" => $data[4],
                            "PIC" => $data[5],
                            "address" => $data[6]
                        ]);

                        $new_delivery_order["project_id"] = $newProject->id;
                    }

                    DeliveryOrder::updateOrCreate(
                        [
                            'register' => $data[0],
                        ],
                        $new_delivery_order
                    );
                }

                fclose($handle);
            }

            // Remove the temporary file
            unlink($tempFilePath);
        }
    }

    public function import_with_product_store(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        // Store the file
        $file = $request->file('csv_file');
        $path = $file->store('csv_files');

        // Process the CSV file
        $this->processdeliveryorderDataCsv2(storage_path('app/' . $path));

        // Delete the stored file after processing
        Storage::delete($path);

        return redirect(route("deliveryorder-index"))->with('successImportDevor', 'Berhasil membaca file CSV dan menambahkan data pengiriman beserta barang-barang di dalamnya.');
    }

    private function processdeliveryorderDataCsv2($filePath)
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
                    $product = Product::where("product_code", $data[1])->first();
                    $devor = DeliveryOrder::where("register", $data[0])->first();

                    if(!$product || !$devor){
                        continue;
                    }

                    $old_stock = $product->stock;

                    if($old_stock - intval($data[2]) >= 0){
                        Product::find($product->id)->update(["stock" => $old_stock - intval($data[2])]);

                        DeliveryOrderProduct::create([
                            "delivery_order_id" => $devor->id,
                            "product_id" => $product->id,
                            "quantity" => intval($data[2])
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
