<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderProduct;
use Illuminate\Support\Facades\Storage;

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
        DeliveryOrder::find($id)->update(["archived" => 1]);

        // Arahkan user kembali ke halaman pages/delivery-order/index.blade.php
        return redirect(route("deliveryorder-index"))->with("successDeleteOrder", "Order deleted successfully!");
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

        return redirect(route("deliveryorder-index"))->with('successImportDevor', 'CSV file uploaded and delivery orders added successfully.');
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

        return redirect(route("deliveryorder-index"))->with('successImportDevor', 'CSV file uploaded and delivery orders added successfully.');
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
