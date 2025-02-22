<?php

namespace App\Imports;

use Exception;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        try {
            DB::beginTransaction();

            $jenis_barang = $row['jenis'];
            $kondisi_barang = $row['kondisi'];

            $product_type = '';
            $product_condition = '';

            switch($kondisi_barang){
                case 'Bagus': $product_condition = 'good'; break;
                case 'Bekas': $product_condition = 'degraded'; break;
                case 'Rekondisi': $product_condition = 'refurbish'; break;
            }

            switch($jenis_barang){
                case 'Fast Moving': $product_type = 'fast moving'; break;
                case 'Aset': $product_type = 'asset'; break;
            }

			Product::create([
                "product_code" => $row['sku'],
                'product_name' => $row['nama_barang'],
                "variant" => $row['varian'],
                "stock" => intval($row['stok']),
                'unit' => $row['satuan'],
                "price" => intval($row['harga']),
                "status" => $row['status'],
                "discount" => floatval(str_replace(',', '.', $row['diskon'])),
                "markup" => floatval(str_replace(',', '.', $row['markup'])),
                'type' => $product_type,
                'condition' => $product_condition,
			]);

            DB::commit();
        }

		catch (Exception $e) {
            DB::rollBack();

			throw $e;
        }
    }
}
