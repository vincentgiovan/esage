<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Project;
use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendancesExport implements FromArray, WithHeadings, WithStyles, WithEvents
{
    /**
     * Retrieve product data and return as an array.
     */
    public function array(): array
    {
        $attendances = Attendance::filter(request(['from', 'until', 'employee', 'project']))
        ->with('project')
            ->orderBy('attendance_date', 'desc')
            ->orderBy(Project::select('project_name')
                ->whereColumn('id', 'attendances.project_id')
                ->limit(1), 'asc')
            ->get();

        // Convert collection to array while ensuring correct data formatting
        $i = 0;
        return $attendances->map(function ($attendance) use (&$i) {
            $i++;

            $total_normal = $attendance->normal * $attendance->employee->pokok;
            $total_lembur = $attendance->jam_lembur * $attendance->employee->lembur;
            $total_lembur_panjang = $attendance->index_lembur_panjang * $attendance->employee->lembur_panjang;

            $subtotal = $total_normal + $total_lembur + $total_lembur_panjang + $attendance->performa;

            return [
                $i,
                Carbon::parse($attendance->attendance_date)->format('d-m-Y'),
                $attendance->project->project_name,
                $attendance->employee->nama,
                strval($attendance->employee->pokok ?? '0'),
                strval($attendance->employee->lembur ?? '0'),
                strval($attendance->employee->lembur_panjang ?? '0'),
                strval($attendance->performa ?? '0'),
                strval($attendance->normal ?? '0'),
                strval($attendance->jam_lembur ?? '0'),
                strval($attendance->index_lembur_panjang ?? '0'),
                strval($subtotal ?? '0'),
                $remark ?? ''
            ];
        })->toArray();
    }

    /**
     * Define the headings for the Excel sheet.
     */
    public function headings(): array
    {
        return ['No', 'Tanggal', 'Proyek', 'Nama', 'Pokok', 'Lembur', 'L. Panjang', 'Performa', 'Normal', 'Lembur', 'Indeks L. Panjang', 'Subtotal', 'Remark'];
    }

    /**
     * Apply styles to the Excel sheet.
     */
    public function styles(Worksheet $sheet)
    {
        // Style for header row (bold, white text, green background, centered)
        $sheet->getStyle('A1:M1')->applyFromArray([
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
                foreach (range('A', 'J') as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }

                // Apply left alignment for all rows EXCEPT the heading (starting from row 2)
                $highestRow = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getStyle('A2:K' . $highestRow)->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
                ]);
            },
        ];
    }
}
