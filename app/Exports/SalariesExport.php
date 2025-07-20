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
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalariesExport implements FromArray, WithStyles, WithEvents
{
    private $start_period, $end_period, $employee, $project;
    private $firstRows = [];

    public function __construct($start_period, $end_period, $employee, $project)
    {
        $this->start_period = $start_period;
        $this->end_period = $end_period;
        $this->employee = $employee;
        $this->project = $project;
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

        $prepaysInThisPeriod = Prepay::filter(request(['from', 'until', 'employee']))->where('enable_auto_cut', 'yes')->get()->groupBy('employee_id');

        $in_current_period = false;

        $today = Carbon::today();
        $rangeStart = Carbon::parse(request('from'));
        $rangeEnd = Carbon::parse(request('until'));

        if ($today >= $rangeStart && $today <= $rangeEnd) {
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

        // Generate the Excel rows
        $i = 1;
        $data_count = 1;

        $finalExcelRows = [];
        foreach($groupedAttendances as $emp_id => $attendances){
            $excelRows = [];

            $employee = Employee::find(intval($emp_id));

            $prepays = $employee->prepays()->where('curr_amount', '>', 0)->where('enable_auto_cut', 'yes')->get();
            $kasubon = $prepays->pluck('id')->toArray();
            $prepay_cuts = PrepayCut::whereIn('prepay_id', $kasubon)->where('start_period', '>=', request('from'))->where('end_period', '<=', request('until'))->get();

            // The header data
            $excelRows[] = [
                $data_count,
                Carbon::parse($this->start_period)->translatedFormat("d/m/Y") . ' - ' . Carbon::parse($this->end_period)->translatedFormat("d/m/Y"),
                $employee->nama,
                $employee->jabatan,
                $subtotals[$emp_id],
                '',
            ];

            $this->firstRows[] = $i;

            // Salary calculation per project
            foreach($attendances->groupBy('project_id') as $proj_id => $gbp){
                $total_jam_normal = 0;
                $total_jam_lembur = 0;
                $total_kali_lembur_panjang = 0;

                $total_gaji = 0;

                $total_gaji_normal = 0;
                $total_gaji_lembur = 0;
                $total_gaji_lembur_panjang = 0;
                $total_performa = 0;

                foreach($gbp as $proj_id => $atd){
                    $project_name = $atd->project->project_name;

                    $total_jam_normal += $atd->normal;
                    $total_jam_lembur += $atd->jam_lembur;
                    $total_kali_lembur_panjang += $atd->index_lembur_panjang;

                    $total_gaji_normal += $atd->normal * $atd->employee->pokok;
                    $total_gaji_lembur += $atd->jam_lembur * $atd->employee->lembur;
                    $total_gaji_lembur_panjang += $atd->index_lembur_panjang * $atd->employee->lembur_panjang;
                    $total_performa += $atd->performa * $atd->employee->performa;

                    $total_gaji += $total_gaji_normal + $total_gaji_lembur + $total_gaji_lembur_panjang + $total_performa + $total_performa;
                }

                if($total_jam_normal != 0){
                    $excelRows[] = [
                        'Normal: ' . $total_jam_normal . ' jam (' . $project_name . ')',
                        '', '', '', '',
                        $total_gaji_normal
                    ];

                    $i++;
                }

                if($total_jam_lembur > 0){
                    $excelRows[] = [
                        'Lembur: ' . $total_jam_lembur . ' jam (' . $project_name . ')',
                        '', '', '', '',
                        $total_gaji_lembur
                    ];

                    $i++;
                }

                if($total_kali_lembur_panjang > 0){
                    $excelRows[] = [
                        'Lembur Panjang: ' . $total_kali_lembur_panjang . ' hari (' . $project_name . ')',
                        '', '', '', '',
                        $total_gaji_lembur_panjang
                    ];

                    $i++;
                }

                if($total_performa > 0){
                    $excelRows[] = [
                        'Performa (' . $project_name . ')',
                        '', '', '', '',
                        $total_performa
                    ];

                    $i++;
                }
            }

            if(!$in_current_period){
                foreach($prepay_cuts as $ppc){
                    $excelRows[] = [
                        'Potongan kasbon untuk ' . $ppc->prepay->remark . ' (Sisa saldo: ' . $ppc->remaining_amount . ')',
                        '', '', '', '',
                        -$ppc->cut_amount
                    ];

                    $i++;
                }
            } else {
                foreach($prepays as $ppay){
                    if($ppay->prepay_date >= request('from') && $ppay->prepay_date <= request('until') == false){
                        continue;
                    }

                    if($total_gaji - $ppay->cut_amount > 0){
                        $excelRows[] = [
                            'Potongan kasbon untuk ' . $ppay->remark . ' (Sisa saldo: ' . ($ppay->curr_amount - $ppay->cut_amount < 0 ? 0 : $ppay->curr_amount - $ppay->cut_amount) . ')',
                            '', '', '', '',
                            -($ppay->curr_amount - $ppay->cut_amount < 0 ? $ppay->curr_amount : $ppay->cut_amount)
                        ];

                        $total_gaji -= $ppay->cut_amount;
                        $i++;
                    }
                }
            }

            $finalExcelRows[] = $excelRows;

            $data_count++;
            $i++;
        }

        return $finalExcelRows;
    }

    /**
     * Apply styles to the Excel sheet.
     */
    public function styles(Worksheet $sheet)
    {
        foreach($this->firstRows as $fr){
            $style = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'yellow']
                ],
            ];

            $sheet->getStyle('A' . $fr)->applyFromArray($style);
            $sheet->getStyle('E' . $fr)->applyFromArray($style);
        }

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

                // Merge cells
                $totalRows = $event->sheet->getDelegate()->getHighestRow();

                for($i = 1; $i <= $totalRows; $i++){
                    if(!in_array($i, $this->firstRows)){
                        $event->sheet->getDelegate()->mergeCells('A'.$i.':E'.$i);
                    }
                }
            },
        ];
    }
}
