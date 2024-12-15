<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Salary;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(){
        return view("pages.employee.index", [
            "employees" => Employee::orderByRaw('CASE WHEN status = "active" THEN 0 ELSE 1 END')->get()
        ]);
    }

    public function show($id){
        return view("pages.employee.show", [
            "employee" => Employee::find($id)
        ]);
    }

    public function create(){
        return view("pages.employee.create", [
            "positions" => Position::all(),
            "specialities" => Speciality::all()
        ]);
    }

    public function store(Request $request){
        $validated_data = $request->validate([
            "nama" => "required|min:3",
            "NIK" => "nullable|min:16",
            "image" => "nullable|file|image|max:4096",
            "kalkulasi_gaji" => "required",
            "jabatan" => "nullable",
            "pokok" => "nullable|numeric|min:0",
            "lembur" => "nullable|numeric|min:0",
            "lembur_panjang" => "nullable|numeric|min:0",
            "performa" => "nullable|numeric|min:0",
            "kasbon" => "nullable|numeric|min:0",
            "payroll" => "required",
            "masuk" => "nullable|date",
            "keluar" => "nullable|date",
            "keterangan" => "nullable",
        ]);

        if($request->file("image")){
			$validated_data["foto_ktp"] = $request->file("image")->store("images");
            unset($validated_data["image"]);
		}

        $validated_data["jabatan"] = Position::find($validated_data["jabatan"])->position_name;

        $selected_specialities = [];
        foreach(Speciality::where("status", "on")->get() as $i => $spc){
            if($request->specialities[$i] == "on"){
                array_push($selected_specialities, $spc->speciality_name);
            }
        }
        $validated_data["keahlian"] = serialize($selected_specialities);

        Employee::create($validated_data);

        return redirect(route("employee-index"))->with("success-add-employee-data", "Berhasil menambahkan data pegawai baru.");
    }

    public function edit($id){
        return view("pages.employee.edit", [
            "employee" => Employee::find($id),
            "positions" => Position::all(),
            "specialities" => Speciality::all()
        ]);
    }

    public function update(Request $request, $id){
        $validated_data = $request->validate([
            "nama" => "required|min:3",
            "NIK" => "nullable|min:16",
            "image" => "nullable|file|image|max:4096",
            "kalkulasi_gaji" => "required",
            "jabatan" => "nullable",
            "pokok" => "nullable|numeric|min:0",
            "lembur" => "nullable|numeric|min:0",
            "lembur_panjang" => "nullable|numeric|min:0",
            "performa" => "nullable|numeric|min:0",
            "kasbon" => "nullable|numeric|min:0",
            "payroll" => "required",
            "masuk" => "nullable|date",
            "keluar" => "nullable|date",
            "keterangan" => "nullable",
            "status" => "required"
        ]);

        $employee = Employee::find($id);

        $validated_data["jabatan"] = Position::find($validated_data["jabatan"])->position_name;

        $selected_specialities = [];
        foreach(Speciality::where("status", "on")->get() as $i => $spc){
            if($request->specialities[$i] == "on"){
                array_push($selected_specialities, $spc->speciality_name);
            }
        }
        $validated_data["keahlian"] = serialize($selected_specialities);

        if($request->file("image")){
			$validated_data["foto_ktp"] = $request->file("image")->store("images");

            if($employee->foto_ktp){
                Storage::delete($employee->foto_ktp);
            }

            unset($validated_data["image"]);
		}

        $employee->update($validated_data);

        return redirect(route("employee-index"))->with("success-edit-employee-data", "Berhasil memperbarui data pegawai.");
    }

    public function manage_form(){
        return view("pages.employee.manage-form", [
            "positions" => Position::all(),
            "specialities" => Speciality::all()
        ]);
    }

    public function manage_form_add_position(Request $request){
        $request->validate(["position_name" => "required|min:3"]);

        Position::create(["position_name" => $request->position_name, "status" => "on"]);

        return back()->with("successAddPosition", "Berhasil menambahkan pilihan posisi baru.");
    }

    public function manage_form_add_speciality(Request $request){
        $request->validate(["speciality_name" => "required|min:3"]);

        Speciality::create(["speciality_name" => $request->speciality_name, "status" => "on"]);

        return back()->with("successAddSpeciality", "Berhasil menambahkan pilihan keahlian baru.");
    }

    public function manage_form_edit_position(Request $request, $id){
        Position::find($id)->update(["status" => $request->status]);

        return back()->with("successEditPosition", "Berhasil memperbarui status pilihan posisi.");
    }

    public function manage_form_edit_speciality(Request $request, $id){
        Speciality::find($id)->update(["status" => $request->status]);

        return back()->with("successEditSpeciality", "Berhasil memperbarui status pilihan keahlian.");
    }

    public function manage_form_delete_position($id){
        Position::find($id)->update(["archived" => 1]);

        return back()->with("successDeletePosition", "Berhasil menghapus pilihan posisi.");
    }

    public function manage_form_delete_speciality($id){
        Speciality::find($id)->update(["archived" => 1]);

        return back()->with("successDeletePosition", "Berhasil menghapus pilihan keahlian.");
    }

}
