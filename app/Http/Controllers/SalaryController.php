<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Salary;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class SalaryController extends Controller
{
    public function index(){
        $groupedAttendances = Attendance::filter(request(['from', 'until', 'employee']))->with('project')
            ->orderBy('attendance_date', 'asc')
            ->orderBy(Employee::select('nama')
                ->whereColumn('id', 'attendances.employee_id')
                ->limit(1), 'asc')
            ->orderBy(Project::select('project_name')
                ->whereColumn('id', 'attendances.project_id')
                ->limit(1), 'asc')
            ->get()
            ->groupBy('employee_id');

        $subtotals = collect(); // Use collection for better handling

        foreach ($groupedAttendances as $employee_id => $attendances) {
            $employee = $attendances->first()->employee; // Get the employee details

            if ($employee->kalkulasi_gaji == "on") {
                $total_salary = 0;

                foreach ($attendances as $atd) {
                    $sub_normal = $atd->normal * $employee->pokok;
                    $sub_lembur = $atd->jam_lembur * $employee->lembur;
                    $sub_lembur_panjang = $atd->index_lembur_panjang * $employee->lembur_panjang;
                    $sub_performa = $atd->performa;

                    $total_salary += $sub_normal + $sub_lembur + $sub_lembur_panjang + $sub_performa;
                }

                $subtotals->put($employee_id, $total_salary);
            }
        }

        // Convert grouped data to a collection for manual pagination
        $groupedCollection = collect($groupedAttendances);

        // Paginate manually
        $page = request()->get('page', 1); // Current page
        $perPage = 30; // Number of employees per page
        $offset = ($page - 1) * $perPage;

        $paginatedAttendances = new LengthAwarePaginator(
            $groupedCollection->slice($offset, $perPage)->all(), // Get current page items
            $groupedCollection->count(), // Total items
            $perPage, // Items per page
            $page, // Current page
            ['path' => request()->url()] // Keep URL pagination links
        );

        // Paginate subtotals to match grouped data pagination
        $paginatedSubtotals = new LengthAwarePaginator(
            $subtotals->slice($offset, $perPage)->all(),
            $subtotals->count(),
            $perPage,
            $page,
            ['path' => request()->url()]
        );

        return view("pages.salary.index", [
            "grouped_attendances" => $paginatedAttendances,
            "subtotals" => $paginatedSubtotals,
            "start_period" => request('from'),
            "end_period" => request('until'),
            "projects" => Project::all(),
        ]);
    }
}
