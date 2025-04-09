<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Partner;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Imports\PartnersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller{
    // Tabel list semua partner
    public function index()
    {
        // Tampilkan halaman pages/partner/index.blade.php beserta data yang diperlukan di blade-nya:
        return view("pages.partner.index", [
            "partners" => Partner::filter(request(['search']))->paginate(30) // semua data partner buat ditampilin satu-satu di tabel
        ]);
    }

    // Form tambah data partner baru
    public function create()
    {
        // Tampilkan halaman pages/partner/create.blade.php
        return view("pages.partner.create");
    }

    // Simpan data partner ke database
    public function store(Request $request)
    {
        // Validasi data, kalo ga sesuai ga lanjut
        $validatedData = $request->validate([
            "partner_name" => "required",
            "role" => "required",
            "remark" => "nullable",
            "address" => "nullable",
            "contact" => ['nullable', 'regex:/^[\d\s+\(\)-]+$/'],
            "phone" => ['nullable', 'regex:/^[\d\s+\(\)-]+$/'],
            "fax" => ['nullable', 'regex:/^[\d\s+\(\)-]+$/'],
            "email" => "nullable|email:dns",
            "tempo" => "nullable"
        ]);

        // Buat dan tambahkan data partner baru ke tabel partners
        Partner::create($validatedData);

        // Arahkan user kembali ke halaman pages/partner/index.blade.php
        return redirect(route("partner-index"))->with("successAddPartner", "Berhasil menambahkan partner baru.");
    }

    // Form edit data partner
    public function edit($id)
    {
        // Tampilkan halaman pages/partner/edit.blade.php beserta data yang diperlukan di blade-nya:
        return view("pages.partner.edit", [
            "partner" => Partner::find($id) // data partner yang mau di-edit buat auto fill form-nya
        ]);
    }

    // Simpan perubahan data partner ke database
    public function update(Request $request, $id)
    {
        // Validasi data, kalo ga sesuai ga lanjut
        $validatedData = $request->validate([
            "partner_name" => "required|min:1",
            "role"=>"required",
            "remark" => "nullable",
            "address" => "nullable",
            "contact"=>['nullable', 'regex:/^[\d\s+\(\)-]+$/'],
            "phone"=>['nullable', 'regex:/^[\d\s+\(\)-]+$/'],
            "fax"=>['nullable', 'regex:/^[\d\s+\(\)-]+$/'],
            "email" => "nullable|email:dns",
            "tempo" => "nullable"
        ]);

        // Simpan perubahan data ke tabel partners
        Partner::find($id)->update($validatedData);

        // Arahkan user kembali ke halaman pages/partner/index.blade.php
        return redirect(route("partner-index"))->with("successEditPartner", "Berhasil memperbaharui data partner.");

    }

    // Hapus partner dari database
    public function destroy($id){
        // Hapus data partner dari tabel partners yang punya id sama kayak data yang mau dihapus
        Partner::find($id)->delete();

        // Arahkan user kembali ke halaman pages/partner/index.blade.php
        return redirect(route("partner-index"))->with("successDeletePartner", "Berhasil menghapus data partner.");
    }

    // READ DATA FROM CSV
    public function import_partner_form(){
        return view("pages.partner.import-data");
    }

    public function import_partner_store(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file_to_upload' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $temp_path = $request->file('file_to_upload')->store('temp');

        try {
			Excel::import(new PartnersImport, $temp_path);

            Storage::delete($temp_path);

			return redirect(route("partner-index"))->with('successImportExcel', 'Berhasil membaca file Excel dan menambahkan data pegawai.');
		}

		catch (Exception $e){
            Storage::delete($temp_path);

            throw $e;

			// return back()->with('failedImportExcel', "Gagal membaca dan menambahkan produk dari file Excel, harap perhatikan format yang telah ditentukan dan silakan coba kembali.");
		}
    }

    private function processpartnerDataCsv($filePath)
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
                    // Insert into the partners table
                    Partner::updateOrCreate(
                        [
                            'partner_name' => $data[0]
                        ],
                        [
                            'role' => $data[1],
                            "remark" => $data[2],
                            "address" => $data[3],
                            "contact" => $data[4],
                            "phone" => $data[5],
                            "fax" => $data[6],
                            "email" => $data[7],
                            "tempo" => $data[8],
                        ]
                    );
                }

                fclose($handle);
            }

            // Remove the temporary file
            unlink($tempFilePath);
        }
    }

    public function view_log($id){
        return view("pages.partner.log", [
            "partner" => Partner::find($id),
        ]);
    }
}
