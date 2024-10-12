<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(){
        return view("pages.employee.index", [
            "employees" => Employee::all()
        ]);
    }

    public function show($id){
        return view("pages.employee.show", [
            "employee" => Employee::find($id)
        ]);
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
        ]);

        $employee = Employee::find($id);

        User::find($employee->user_id)->update(["name" => $validated_data["nama"]]);
        unset($validated_data["nama"]);

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
            unset($validated_data["image"]);
		}

        $employee->update($validated_data);

        return redirect(route("employee-index"))->with("success-edit-employee-data");
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

        return back()->with("successAddPosition", "Position added successfully!");
    }

    public function manage_form_add_speciality(Request $request){
        $request->validate(["speciality_name" => "required|min:3"]);

        Speciality::create(["speciality_name" => $request->speciality_name, "status" => "on"]);

        return back()->with("successAddSpeciality", "Speciality added successfully!");
    }

    public function manage_form_edit_position(Request $request, $id){
        Position::find($id)->update(["status" => $request->status]);

        return back()->with("successEditPosition", "Position edited successfully!");
    }

    public function manage_form_edit_speciality(Request $request, $id){
        Speciality::find($id)->update(["status" => $request->status]);

        return back()->with("successEditSpeciality", "Speciality edited successfully!");
    }

    public function manage_form_delete_position($id){
        Position::find($id)->delete();

        return back()->with("successDeletePosition", "Position deleted successfully!");
    }

    public function manage_form_delete_speciality($id){
        Speciality::find($id)->delete();

        return back()->with("successDeletePosition", "Position deleted successfully!");
    }

}
