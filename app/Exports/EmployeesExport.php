<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class EmployeesExport implements FromArray, WithHeadings, WithStyles, WithEvents, WithColumnFormatting
{
    /**
     * Retrieve product data and return as an array.
     */
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT
        ];
    }

    public function array(): array
    {
        $employees = Employee::orderByRaw('CASE WHEN status = "active" THEN 0 ELSE 1 END')->orderBy('nama')->get();

        // Convert collection to array while ensuring correct data formatting
        $i = 0;

        return $employees->map(function ($employee) use (&$i) {
            $i++;

            $total_kasbon = 0;
            foreach($employee->prepays as $ppay){
                $total_kasbon += $ppay->curr_amount;
            }

            return [
                $i,
                $employee->nama ?? '',
                $employee->NIK ? (string)$employee->NIK : '',
                $employee->jabatan ?? '',
                $employee->pokok ?? 0,
                $employee->lembur ?? 0,
                $employee->lembur_panjang ?? 0,
                $total_kasbon,
                $employee->keterangan ?? 0,
                $employee->masuk ?? '',
                $employee->keluar ?? '',
                $employee->status == 'active' ? 'Aktif' : 'Tidak aktif',
            ];
        })->toArray();
    }

    /**
     * Define the headings for the Excel sheet.
     */
    public function headings(): array
    {
        return ['No', 'Nama', 'NIK', 'Jabatan', 'Pokok', 'Lembur', 'Lembur Panjang', 'Kasbon', 'Keterangan', 'Masuk', 'Keluar', 'Status'];
    }

    /**
     * Apply styles to the Excel sheet.
     */
    public function styles(Worksheet $sheet)
    {
        // Style for header row (bold, white text, green background, centered)
        $sheet->getStyle('A1:L1')->applyFromArray([
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
