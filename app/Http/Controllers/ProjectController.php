<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;

class ProjectController extends Controller{
    // Tabel list semua project
    public function index()
    {
        // Tampilkan halaman pages/project/index.blade.php beserta data yang diperlukan:
        return view("pages.project.index", [
            "projects" => Project::all() // semua data project buat ditampilin satu-satu
        ]);
    }

    // Form input project baru
    public function create()
    {
        // Tampilkan halaman pages/project/create.blade.php
        return view("pages.project.create");
    }

    // Simpan data project baru ke database
    public function store(Request $request)
    {
        // Validasi data, kalo ga lolos ga lanjut
        $validatedData = $request->validate([
            "project_name" => "required|min:3",
            "location"=>"required",
            "PIC" => "required|min:3",
            "address" => "required"
        ]);

        // Buat dan tambahkan data project baru ke tabel projects
        Project::create($validatedData);

        // Arahkan user kembali ke halaman pages/project/index.blade.php
        return redirect(route("project-index"))->with("successAddProject", "Project added successfully!");
    }

    // Form edit data project
    public function edit($id)
    {
        // Tampilkan halaman pages/project/edit.blade.php beserta data yang diperlukan di blade-nya:
        return view("pages.project.edit", [
            "project" => Project::where("id", $id)->first() // data project yang mau di-edit buat autofill data di form
        ]);
    }

    // Simpan data project ke database
    public function update(Request $request, $id)
    {
        // Validasi data, ga lolos ga lanjut
        $validatedData = $request->validate([
            "project_name" => "required|min:3",
            "location"=>"required",
            "PIC" => "required|min:3",
            "address" => "required"
        ]);

        // Simpan perubahan datanya di tabel projects
        Project::where("id", $id)->update($validatedData);

        // Arahkan user kembali ke halaman pages/project/index.blade.php
        return redirect(route("project-index"))->with("successEditProject", "Project editted successfully!");
    }

    // Hapus data project dari database
    public function destroy($id)
    {
        // Hapus data project yang mau dihapus dari tabel projects
        Project::destroy("id", $id);

        // Arahkan user kembali ke halaman pages/project/index.blade.php
        return redirect(route("project-index"))->with("successDeleteProject", "Project deleted successfully!");
    }
}
