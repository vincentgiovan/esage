<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(){
        $attendances = Attendance::orderBy('attendance_date', 'asc')->get();
        $subtotals = [];

        foreach($attendances as $atd){
            if($atd->employee->kalkulasi_gaji == "on"){
                $sub_normal = $atd->normal * $atd->employee->pokok;
                $sub_lembur = $atd->jam_lembur * $atd->employee->lembur;
                $sub_lembur_panjang = $atd->index_lembur_panjang * $atd->employee->lembur_panjang;
                $sub_performa = $atd->index_performa * $atd->employee->performa;

                $subtotal = $sub_normal + $sub_lembur + $sub_lembur_panjang + $sub_performa;
                array_push($subtotals, $subtotal);
            }
            else {
                array_push($subtotals, 'N/A');
            }
        }

        return view("pages.attendance.index", [
            "attendances" => $attendances,
            "subtotals" => $subtotals
        ]);
    }

    public function create_admin(){
        return view("pages.attendance.create-admin", [
            "projects" => Project::all(),
            "employees" => Employee::all()
        ]);
    }

    public function create_self(){
        return view("pages.attendance.create-self", [
            "projects" => Project::all(),
            "employees" => Employee::all()
        ]);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            "attendance_date" => "required",
            "employee_id" => "required",
            "project_id" => "required",
            "normal" => "required|numeric|min:0",
            "jam_lembur" => "required|numeric|min:0",
            "index_lembur_panjang" => "required|numeric|min:0",
            "index_performa" => "required|numeric|min:0",
            "remark" => "nullable",
            "latitude" => "required",
            "longitude" => "required"
        ]);

        Attendance::create($validatedData);

        return redirect(route("attendance-index"))->with("successAddAttendance", "New attendance added sucessfully!");
    }

    public function edit($id){
        return view("pages.attendance.edit", [
            "attendance" => Attendance::find($id),
            "projects" => Project::all(),
            "employees" => Employee::all()
        ]);
    }

    public function update(Request $request, $id){
        $validatedData = $request->validate([
            "attendance_date" => "required",
            "employee_id" => "required",
            "project_id" => "required",
            "normal" => "required|numeric|min:0",
            "jam_lembur" => "required|numeric|min:0",
            "index_lembur_panjang" => "required|numeric|min:0",
            "index_performa" => "required|numeric|min:0",
            "remark" => "nullable"
        ]);

        Attendance::find($id)->update($validatedData);

        return redirect(route("attendance-index"))->with("successEditAttendance", "New attendance edited sucessfully!");
    }

    public function destroy($id){
        Attendance::find($id)->delete();

        return redirect(route("attendance-index"))->with("successDeleteAttendance", "New attendance deleted sucessfully!");
    }

    public function location($id){
        return view("pages.attendance.check-location", [
            "attendance" => Attendance::find($id)
        ]);
    }
}
