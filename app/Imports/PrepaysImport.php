<?php

namespace App\Imports;

use Exception;
use Carbon\Carbon;
use App\Models\Prepay;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PrepaysImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        try {
            DB::beginTransaction();

            $employee = Employee::where('nama', 'like', '%' . $row['nama_pegawai'] . '%')->first();

			Prepay::create([
                "employee_id" => $employee->id,
                'prepay_date' => Carbon::today()->format('Y-m-d'),
                "init_amount" => intval($row['nilai_awal']),
                'curr_amount' => intval($row['saldo']),
                'cut_amount' => intval($row['pemotongan']),
                'remark' => $row['keperluan'],
                'enable_auto_cut' => 'yes',
			]);

            DB::commit();
        }

		catch (Exception $e) {
            DB::rollBack();

			throw $e;
        }
    }
}
