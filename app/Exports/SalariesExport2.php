<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Prepay;
use App\Models\Project;
use App\Models\Employee;
use App\Models\PrepayCut;
use App\Models\Attendance;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalariesExport2 implements FromArray, WithStyles, WithEvents, WithHeadings
{
    private $start_period, $end_period, $employee, $project;

    public function __construct($start_period, $end_period, $employee, $project)
    {
        $this->start_period = $start_period;
        $this->end_period = $end_period;
        $this->employee = $employee;
        $this->project = $project;
    }

    public function headings(): array{
        return [
            'No', 'Periode', 'Nama', 'Jabatan', 'Total'
        ];
    }

    public function array(): array
    {
        // Retrieve all the attendance data
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

        $prepaysInThisPeriod = Prepay::filter(request(['from', 'until', 'employee']))->where('curr_amount', '>', 0)->where('enable_auto_cut', 'yes')->get()->groupBy('employee_id');

         // Check if the attendances data are in the same period as today

        // Check if the attendances data are in the same period as today
        $in_current_period = false;

        $today = Carbon::today();
        $thisWeeksFriday = $today->copy()->endOfWeek(Carbon::FRIDAY);
        $lastWeeksSaturday = $today->copy()->previous(Carbon::SATURDAY);

        $rangeStart = Carbon::parse(request('from'));
        $rangeEnd = Carbon::parse(request('until'));

        if ($rangeStart->greaterThanOrEqualTo($lastWeeksSaturday) && $rangeEnd->lessThanOrEqualTo($thisWeeksFriday)) {
            $in_current_period = true;
        } else {
            $in_current_period = false;
        }

        // Calculate the total salary
        $subtotals = [];

        foreach($groupedAttendances as $employee_id => $attendances){
            $employee = Employee::find($employee_id);

            if($employee->kalkulasi_gaji == "on"){
                $total_salary = 0;

                foreach($attendances as $atd){
                    $sub_normal = $atd->normal * $atd->employee->pokok;
                    $sub_lembur = $atd->jam_lembur * $atd->employee->lembur;
                    $sub_lembur_panjang = $atd->index_lembur_panjang * $atd->employee->lembur_panjang;
                    $sub_performa = $atd->performa * $atd->employee->performa;

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
                else {
                    $prepay_cuts = PrepayCut::whereHas('prepay', function($query) use ($employee){
                        $query->where('employee_id', $employee->id);
                    })->where('start_period', request('from'))->where('end_period', request('until'))->get();

                    foreach($prepay_cuts as $ppc){
                        $total_salary -= $ppc->cut_amount;
                    }
                }

                $subtotals[$employee_id] = $total_salary;
            }
            else {
                array_push($subtotals, 'N/A');
            }
        }

        // Generate the Excel rows
        $data_count = 1;

        $finalExcelRows = [];
        foreach($groupedAttendances as $emp_id => $attendances){
            $excelRows = [];

            $employee = Employee::find(intval($emp_id));

            // The header data
            $excelRows[] = [
                $data_count,
                Carbon::parse($this->start_period)->translatedFormat("d/m/Y") . ' - ' . Carbon::parse($this->end_period)->translatedFormat("d/m/Y"),
                $employee->nama,
                $employee->jabatan,
                $subtotals[$emp_id],
            ];

            $finalExcelRows[] = $excelRows;
        }

        return $finalExcelRows;
    }

    /**
     * Apply styles to the Excel sheet.
     */
    public function styles(Worksheet $sheet)
    {
        // Style for header row (bold, white text, green background, centered)
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], // White text
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '696969'] // Green background
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER] // Center align headers
        ]);

        return [];
    }

    /**
     * Auto-size columns and set text alignment for body cells.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Auto-size all columns
                foreach (range('A', 'E') as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }

                // Apply left alignment for all rows EXCEPT the heading (starting from row 2)
                $highestRow = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getStyle('A1:E' . $highestRow)->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
                ]);
            },
        ];
    }
}
