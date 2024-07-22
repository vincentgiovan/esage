<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Partner;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;

class PartnerController extends Controller{
    // Tabel list semua partner
    public function index()
    {
        // Tampilkan halaman pages/partner/index.blade.php beserta data yang diperlukan di blade-nya:
        return view("pages.partner.index", [
            "partners" => Partner::all() // semua data partner buat ditampilin satu-satu di tabel
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
            "partner_name" => "required|min:1",
            "role" => "required",
            "remark" => "nullable",
            "address" => "required|min:0",
            "contact" => "required|numeric|min:7|not_in:0",
            "phone" => "required|numeric|min:1|not_in:0",
            "fax" => "required|numeric|min:1|not_in:0",
            "email" => "required|email:dns",
            "tempo" => "nullable"
        ]);

        // Buat dan tambahkan data partner baru ke tabel partners
        Partner::create($validatedData);

        // Arahkan user kembali ke halaman pages/partner/index.blade.php
        return redirect(route("partner-index"))->with("successAddPartner", "Partner added successfully!");
    }

    // Form edit data partner
    public function edit($id)
    {
        // Tampilkan halaman pages/partner/edit.blade.php beserta data yang diperlukan di blade-nya:
        return view("pages.partner.edit", [
            "partner" => Partner::where("id", $id)->first() // data partner yang mau di-edit buat auto fill form-nya
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
            "address" => "required|min:0",
            "contact"=>"required|min:7|not_in:0",
            "phone"=>"required|min:1|not_in:0",
            "fax"=>"required|min:1|not_in:0",
            "email" => "required|email:dns",
            "tempo" => "nullable"
        ]);

        // Simpan perubahan data ke tabel partners
        Partner::where("id", $id)->update($validatedData);

        // Arahkan user kembali ke halaman pages/partner/index.blade.php
        return redirect(route("partner-index"))->with("successEditPartner", "Partner editted successfully!");

    }

    // Hapus partner dari database
    public function destroy($id){
        // Hapus data partner dari tabel partners yang punya id sama kayak data yang mau dihapus
        Partner::destroy("id", $id);

        // Arahkan user kembali ke halaman pages/partner/index.blade.php
        return redirect(route("partner-index"))->with("successDeletePartner", "Partner deleted successfully!");
    }
}
