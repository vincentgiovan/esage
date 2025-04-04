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
        $groupedAttendances = Attendance::filter(request(['from', 'until']))->with('project')
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
            "projects" => Project::all()
        ]);
    }

    public function auto_create(){
        // Step 1: Fetch last upload date
        $lastDate = DB::table('salaries')->max('created_at');
        $lastDate = $lastDate ? Carbon::parse($lastDate) : Carbon::parse(DB::table('attendances')->min('created_at'))->previous('Saturday')->previous('Saturday'); // Example start date

        // Step 2: Determine today's date
        $today = Carbon::now();

        // Step 3: Find the first Saturday after last upload date
        $currentSaturday = $lastDate->copy()->next('Saturday');

        // Step 4: Loop through each week until today
        while ($currentSaturday->addDays(6) < $today) { // Loop until next Friday of the current week
            $startOfWeek = $currentSaturday->copy()->subDays(6); // Saturday
            $endOfWeek = $currentSaturday->copy(); // Friday

            $employees = Employee::where("kalkulasi_gaji", "on")->where("status", "active")->get();

            foreach ($employees as $employee) {
                $attendances = $employee->attendances()
                    ->whereBetween('attendance_date', [$startOfWeek, $endOfWeek])
                    ->get();

                $total = 0;
                foreach ($attendances as $atd) {
                    $sub_normal = $atd->normal * $employee->pokok;
                    $sub_lembur = $atd->jam_lembur * $employee->lembur;
                    $sub_lembur_panjang = $atd->index_lembur_panjang * $employee->lembur_panjang;
                    $sub_performa = $atd->performa;
                    $total += $sub_normal + $sub_lembur + $sub_lembur_panjang + $sub_performa;
                }


                if (!Salary::where('employee_id', $employee->id)
                    ->where('start_period', $startOfWeek)
                    ->where('end_period', $endOfWeek)
                    ->exists()) {
                    Salary::create([
                        "employee_id" => $employee->id,
                        "start_period" => $startOfWeek,
                        "end_period" => $endOfWeek,
                        "keterangan" => "Auto generated by system",
                        "total" => $total
                    ]);
                }
            }

            // Move to the next Saturday
            $currentSaturday = $endOfWeek->copy()->addDay();
        }

        return redirect(route('salary-index'))->with('successAutoGenerateSalary', 'Data gaji pegawai terbaru telah berhasil digenerasi!');
    }

    public function edit($id){
        return view("pages.salary.edit", [
            "salary" => Salary::find($id)
        ]);
    }

    public function update(Request $request, $id){
        Salary::find($id)->update(["keterangan" => $request->keterangan]);

        return redirect(route('salary-index'))->with("successEditSalary", "Berhasil memperbaharui data gaji pegawai.");
    }
}
