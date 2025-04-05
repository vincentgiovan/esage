<?php

namespace App\Imports;

use Exception;
use App\Models\Product;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        try {
            DB::beginTransaction();

			Employee::create([
                "nama" => $row['name'],
                'NIK' => strval($row['nik_ktp']),
                "jabatan" => $row['jabatan'],
                'keahlian' => serialize([]),
                "pokok" => intval($row['pokok']),
                "lembur" => intval($row['lembur']),
                "lembur_panjang" => intval($row['lembur_panjang']),
                'old_kasbon' => intval($row['kasbon']),
                'kalkulasi_gaji' => $row['kalkulasi_gaji'] == 'Ya' ? 'on' : 'off',
                "masuk" => $row['masuk'] != '' ? Carbon::parse($row['masuk'])->format('Y-m-d') : null,
                "keluar" => $row['keluar'] != '' ? Carbon::parse($row['keluar'])->format('Y-m-d') : null,
                'keterangan' => $row['keterangan'] ?? '',
                'status' => str_contains($row['name'], "OFF") ? 'passive' : 'active',
			]);

            DB::commit();
        }

		catch (Exception $e) {
            DB::rollBack();

			throw $e;
        }
    }
}
