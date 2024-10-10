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
            "foto_ktp" => "nullable|file|image|max:4096",
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

        $employee->update($validated_data);

        return redirect(route("employee-index"))->with("success-edit-employee-data");
    }


}
