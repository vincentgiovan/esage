<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ProductsExport implements FromArray, WithHeadings, WithStyles, WithEvents
{
    /**
     * Retrieve product data and return as an array.
     */
    public function array(): array
    {
        // $products = Product::with(['purchase_products' => function ($query) {
        //     $query->with(['purchase' => function ($query) {
        //         $query->latest('purchase_date'); // Get latest purchase
        //     }]);
        // }])
        // ->orderBy('product_name')
        // ->orderBy('variant')
        // ->get();

        $products = Product::with(['latest_purchase_product.purchase'])
            ->orderBy('product_name')
            ->orderBy('variant')
            ->get();

        // Convert collection to array while ensuring correct data formatting
        $i = 0;

        return $products->map(function ($product) use (&$i) {
            $i++;

            $firstPurchaseDate = $product->latest_purchase_product?->purchase?->purchase_date ?? '';

            return [
                $i,
                $product->product_code ?? '',
                $product->product_name ?? '',
                $product->variant ?? '',
                $product->stock !== null ? (int) $product->stock : 0, // Ensures 0 is included for stock (integer)
                $product->unit ?? '',
                $firstPurchaseDate,
                $product->price !== null ? (int) $product->price : 0, // Ensures 0 is included for price (integer)
                $product->discount !== null ? number_format((float) $product->discount, 2, '.', '') : 0, // Keeps 2 decimal places for discount
                $product->markup !== null ? number_format((float) $product->markup, 2, '.', '') : 0, // Keeps 2 decimal places for markup
                $product->type ? ucwords($product->type) : '',
                $product->condition == 'good' ? 'Bagus' : ($product->condition == 'degraded' ? 'Bekas' : 'Rekondisi'),
            ];
        })->toArray();
    }

    /**
     * Define the headings for the Excel sheet.
     */
    public function headings(): array
    {
        return ['No', 'SKU', 'Nama Produk', 'Varian', 'Stok', 'Satuan', 'Tanggal Beli', 'Harga', 'Diskon', 'Markup', 'Jenis', 'Kondisi'];
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
