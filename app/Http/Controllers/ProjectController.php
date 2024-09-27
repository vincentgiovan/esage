<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use Illuminate\Support\Facades\Storage;

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

    // READ DATA FROM CSV
    public function import_project_form(){
        return view("pages.project.import-data");
    }

    public function import_project_store(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        // Store the file
        $file = $request->file('csv_file');
        $path = $file->store('csv_files');

        // Process the CSV file
        $this->processProjectDataCsv(storage_path('app/' . $path));

        // Delete the stored file after processing
        Storage::delete($path);

        return redirect(route("project-index"))->with('success', 'CSV file uploaded and products added successfully.');
    }

    private function processProjectDataCsv($filePath)
    {
        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            // Read the entire CSV file content
            $fileContent = file_get_contents($filePath);

            // Replace semicolons with commas
            $fileContent = str_replace(';', ',', $fileContent);

            // Create a temporary file with the corrected content
            $tempFilePath = tempnam(sys_get_temp_dir(), 'csv');
            file_put_contents($tempFilePath, $fileContent);

            // Re-open the temporary file for processing
            if (($handle = fopen($tempFilePath, 'r')) !== FALSE) {
                // Skip the header row if it exists
                $header = fgetcsv($handle);

                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    // Insert into the projects table
                    Project::updateOrCreate(
                        [
                            'project_name' => $data[0],
                        ], [
                            'location' => $data[1],
                            "PIC" => $data[2],
                            "address" => $data[3],
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
        return view("pages.project.log", ["project" => Project::find($id)]);
    }
}
