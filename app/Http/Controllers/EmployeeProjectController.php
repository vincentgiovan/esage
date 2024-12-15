<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Employee;
use App\Models\EmployeeProject;
use Illuminate\Http\Request;

class EmployeeProjectController extends Controller
{
    public function index($id){
        $order = 'CASE
                    WHEN jabatan LIKE "%Manage%" THEN 1
                    WHEN jabatan = "Kepala Tukang" THEN 2
                    WHEN jabatan = "Tukang" THEN 3
                    WHEN jabatan = "1/2 Tukang" THEN 4
                    WHEN jabatan = "Mandor" THEN 5
                    WHEN jabatan = "Laden" THEN 6
                    ELSE 7
                END';

        $project = Project::find($id);
        $all_employees = Employee::where('status', 'active')->orderByRaw($order)->get();
        $ep = EmployeeProject::where("project_id", $project->id)->get();

        $project_employee_ids = $ep->pluck('employee_id');
        $filtered_employees = $all_employees->whereNotIn('id', $project_employee_ids);

        return view('pages.project.manage-employee', [
            "project" => $project,
            "all_employees" => $filtered_employees,
            "employees_assigned" => $project->employees
        ]);
    }

    public function assign_employee(Request $request, $id){
        $project = Project::find($id);

        EmployeeProject::create(["project_id" => $project->id, "employee_id" => $request->employee]);

        return back()->with('successAssignEmployee', 'Berhasil menambahkan pegawai ke proyek.');
    }

    public function unassign_employee(Request $request, $id){
        $project = Project::find($id);
        $employee = Employee::find($request->employee);

        $del = EmployeeProject::where("project_id", $project->id)->where("employee_id", $employee->id);
        $del->delete();

        return back()->with('successUnassignEmployee', 'Berhasil menghapus pegawai dari proyek.');
    }
}
