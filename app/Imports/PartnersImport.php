<?php

namespace App\Imports;

use Exception;
use Carbon\Carbon;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PartnersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        try {
            DB::beginTransaction();

			Partner::create([
                "partner_name" => $row['nama'],
                'contact' => strval($row['kontak']),
                "address" => $row['alamat'],
                'phone' => strval($row['telp']),
                "role" => 'Supplier',
                'remark' => $row['remark'],
			]);

            DB::commit();
        }

		catch (Exception $e) {
            DB::rollBack();

			throw $e;
        }
    }
}
