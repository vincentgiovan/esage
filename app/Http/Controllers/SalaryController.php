<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Prepay;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Exports\SalariesExport;
use App\Exports\SalariesExport2;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator;

class SalaryController extends Controller
{
    public function index(Request $request){
        $groupedAttendances = Attendance::filter(request(['from', 'until', 'employee', 'project']))->with('project')
            ->orderBy('attendance_date', 'asc')
            ->orderBy(Employee::select('nama')
                ->whereColumn('id', 'attendances.employee_id')
                ->limit(1), 'asc')
            ->orderBy(Project::select('project_name')
                ->whereColumn('id', 'attendances.project_id')
                ->limit(1), 'asc')
            ->get()
            ->groupBy('employee_id');

        $prepaysInThisPeriod = Prepay::filter(request(['from', 'until', 'employee']))->where('enable_auto_cut', 'yes')->get()->groupBy('employee_id');

        $in_current_period = false;

        $today = Carbon::today();
        $lastWeeksSaturday = $today->copy()->previous(Carbon::SATURDAY);;
        $thisWeeksFriday = $today->copy()->endOfWeek(Carbon::FRIDAY);

        $rangeStart = Carbon::parse(request('from'));
        $rangeEnd = Carbon::parse(request('until'));

        if ($rangeStart->greaterThanOrEqualTo($lastWeeksSaturday) && $rangeEnd->lessThanOrEqualTo($thisWeeksFriday)) {
            $in_current_period = true;
        } else {
            $in_current_period = false;
        }

        $subtotals = collect(); // Use collection for better handling
        $total_all = 0;

        foreach ($groupedAttendances as $employee_id => $attendances) {
            $employee = $attendances->first()->employee; // Get the employee details

            if ($employee->kalkulasi_gaji == "on") {
                $total_salary = 0;

                foreach ($attendances as $atd) {
                    $sub_normal = $atd->normal * $employee->pokok;
                    $sub_lembur = $atd->jam_lembur * $employee->lembur;
                    $sub_lembur_panjang = $atd->index_lembur_panjang * $employee->lembur_panjang;
                    $sub_performa = $atd->performa * $employee->performa;

                    $total_salary += $sub_normal + $sub_lembur + $sub_lembur_panjang + $sub_performa;
                }

                // To show the prepay cut result on the given period
                if($in_current_period && isset($prepaysInThisPeriod[strval($employee_id)])){
                    foreach($prepaysInThisPeriod[strval($employee_id)] as $ppay){
                        if($total_salary > 0){
                            $total_salary -= $ppay->cut_amount;
                        }
                    }
                }

                $subtotals->put($employee_id, $total_salary);
                $total_all += $total_salary;
            }
        }

        // Convert grouped data to a collection for manual pagination
        $groupedCollection = collect($groupedAttendances);

        $showAllData = false;
        if($request['show'] && $request['show'] == 'all'){
            $showAllData = true;
        }

        if(!$showAllData){
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
        }

        return view("pages.salary.index", [
            "grouped_attendances" => $showAllData ? $groupedAttendances : $paginatedAttendances,
            "subtotals" => $showAllData ? $subtotals : $paginatedSubtotals,
            'total_all' => $total_all,
            'is_paginated' => !$showAllData,
            "start_period" => request('from'),
            "end_period" => request('until'),
            "projects" => Project::all(),
        ]);
    }

    public function export_salaries_excel(Request $request){
        return Excel::download(new SalariesExport($request->from, $request->until, $request->employee, $request->project), 'slip-gaji.xlsx');
    }

    public function export_salaries_excel_2(Request $request){
        return Excel::download(new SalariesExport2($request->from, $request->until, $request->employee, $request->project), 'slip-gaji.xlsx');
    }
}
