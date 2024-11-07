<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(){
        return view("pages.attendance.index", [
            "attendances" => Attendance::all()
        ]);
    }

    public function create(){
        return view("pages.attendance.create", [
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
            "remark" => "nullable",
        ]);

        Attendance::find($id)->update($validatedData);

        return redirect(route("attendance-index"))->with("successEditAttendance", "New attendance edited sucessfully!");
    }

    public function destroy($id){
        Attendance::find($id)->delete();

        return redirect(route("attendance-index"))->with("successDeleteAttendance", "New attendance deleted sucessfully!");
    }
}
