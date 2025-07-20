<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Prepay;
use App\Models\Employee;
use App\Models\PrepayCut;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Imports\PrepaysImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PrepayController extends Controller
{
    public function index($emp_id){
        $raw_prepays = Prepay::where('employee_id', $emp_id)->orderBy('prepay_date', 'desc');
        $prepay_ids = $raw_prepays->clone()->pluck('id')->toArray();
        $prepays_paginated = $raw_prepays->paginate(30);
        $prepay_cuts = PrepayCut::whereIn('prepay_id', $prepay_ids)->orderBy('start_period')->paginate(30);

        return view('pages.prepay.index', [
            'prepays' => $prepays_paginated,
            'prepay_cuts' => $prepay_cuts,
            'employee' => Employee::find($emp_id),
        ]);
    }

    public function create($emp_id){
        return view('pages.prepay.create', [
            'employee' => Employee::find($emp_id)
        ]);
    }

    public function store(Request $request){
        // Use Validator to handle manual validation checks
        $validated_data = $request->validate([
            'employee_id' => 'required',
            "prepay_date" => "required",
            "init_amount" => "required|numeric|min:0|not_in:0",
            "cut_amount" => "required|numeric|min:0|not_in:0",
            "remark" => "required|string",
            'enable_auto_cut' => 'required',
        ]);

        $validated_data['curr_amount'] = $validated_data['init_amount'];

        Prepay::create($validated_data);

        return redirect(route('prepay-index', $validated_data['employee_id']))->with('successAddPrepay', 'Berhasil menambahkan data kasbon baru untuk pegawai ini.');
    }

    public function edit($emp_id, $ppay_id){
        return view('pages.prepay.edit', [
            'prepay' => Prepay::find($ppay_id),
            'employee' => Employee::find($emp_id)
        ]);
    }

    public function update(Request $request, $emp_id, $ppay_id){
        $validated_data = $request->validate([
            "prepay_date" => "required",
            "init_amount" => "required|numeric|min:0|not_in:0",
            'curr_amount' => 'required|numeric|min:0',
            "cut_amount" => "required|numeric|min:0|not_in:0",
            "remark" => "nullable",
            'enable_auto_cut' => 'required',
        ]);

        $prepay = Prepay::find($ppay_id);
        $new_cut_amount = $validated_data['cut_amount'];

        $prepay->update($validated_data);

        foreach ($prepay->prepay_cuts as $ppc) {
            $prev_cut_amount = $ppc->cut_amount;
            $prev_curr_amount = $ppc->curr_amount;
            $init_amount = $ppc->init_amount;

            $difference = $new_cut_amount - $prev_cut_amount;

            $ppc->update([
                'cut_amount' => $new_cut_amount,
                'curr_amount' => $prev_curr_amount + $difference,
                'remaining_amount' => $init_amount - $new_cut_amount,
            ]);
        }

        return redirect(route('prepay-index', $emp_id))->with('successEditPrepay', 'Berhasil memperbaharui data kasbon untuk pegawai ini.');
    }

    public function destroy($emp_id, $ppay_id){
        $prepay = Prepay::find($ppay_id);
        $prepay->delete();

        return redirect(route('prepay-index', $emp_id))->with('successDeletePrepay', 'Berhasil menghapus data kasbon dari pegawai ini.');
    }

    // For simulation purpose only
    public function generate() {
        // Sekip kalo bukan hari Senin
        $today = Carbon::today();

        if(!$today->isSaturday()) {
            return response()->json(['success' => false, 'message' => 'Belum saatnya untuk pemotongan kasbon']);
        }

        // Ambil periode terakhir
        $prev_end_period = $today->copy()->previous(Carbon::FRIDAY)->startOfDay();
        $prev_start_period = $prev_end_period->copy()->previous(Carbon::SATURDAY)->startOfDay();

        // Kalau udah pernah ada data potongan ke-generate, sekip
        $existing_prepay_cuts = PrepayCut::where('start_period', $prev_start_period)->where('end_period', $prev_end_period)->get()->count();

        if($existing_prepay_cuts > 0){
            return response()->json(['success' => false, 'message' => 'Data pemotongan kasbon telah dibuat beberapa saat sebelumnya.']);
        }

        try {
            DB::beginTransaction();

            // Ambil semua data kasbon di periode sebelumnya yang auto-cut dan belum habis, kelompokkan berdasarkan pegawai
            $prepaysInThisPeriod = Prepay::where('prepay_date', '>=', $prev_start_period)->where('prepay_date', '<=', $prev_end_period)->where('enable_auto_cut', 'yes')->where('curr_amount', '>', 0)->get()->groupBy('employee_id');

            foreach ($prepaysInThisPeriod as $employee_id => $prepays) {
                // Ambil info pegawai
                $employee = Employee::find($employee_id);

                // Lakukan hanya kalau si pegawai diterapkan kalkulasi gaji
                if ($employee->kalkulasi_gaji == "on") {
                    $total_salary = 0;

                    // Untuk setiap data presensi di periode yang sama...
                    foreach (Attendance::where('attendance_date', '>=', $prev_start_period)->where('attendance_date', '<=', $prev_end_period)->where('employee_id', $employee_id)->get() as $atd) {
                        // Hitung gaji kotornya
                        $sub_normal = $atd->normal * $employee->pokok;
                        $sub_lembur = $atd->jam_lembur * $employee->lembur;
                        $sub_lembur_panjang = $atd->index_lembur_panjang * $employee->lembur_panjang;
                        $sub_performa = $atd->performa * $employee->performa;

                        $total_salary += $sub_normal + $sub_lembur + $sub_lembur_panjang + $sub_performa;
                    }

                    // Untuk setiap kasbon yang si pegawai punya...
                    foreach($prepays as $ppay){
                        // Kalo gaji kotor yang diterima masih cukup buat motong kasbon...
                        if($total_salary > 0){

                            // Tentuin jumlah pemotongan (habisin kalo lebih kecil dari jumlah pemotongan)
                            $cut_amount = min($ppay->cut_amount, $ppay->curr_amount);

                            // Tentuin sisa saldo
                            $remaining_amount = $ppay->curr_amount - $cut_amount;

                            // Kalo sisa saldo habis matiin auto-cut
                            $enable_auto_cut = $remaining_amount <= 0 ? 'no' : 'yes';

                            // Bikin data pemotongan kasbon
                            PrepayCut::create([
                                'prepay_id' => $ppay->id,
                                'start_period' => $prev_start_period,
                                'end_period' => $prev_end_period,
                                'init_amount' => $ppay->curr_amount,
                                'cut_amount' => $cut_amount,
                                'remaining_amount' => $remaining_amount,
                            ]);

                            // Update juga data kasbonnya
                            $ppay->update([
                                'curr_amount' => $remaining_amount,
                                'enable_auto_cut' => $enable_auto_cut,
                            ]);

                            // Gaji yang diterima si pegawai dikurangin sama besar pemotongan kasbon buat dicek masih bisa motong lagi apa ngga
                            $total_salary -= $cut_amount;
                        }
                    }
                }
            }

            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();

            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat membuat pemotongan kasbon: ' . $e->getMessage()]);
        }

        return response()->json(['success' => true, 'message' => 'Pemotongan kasbon berhasil dibuat!']);
    }

    public function import_prepay_form(){
        return view("pages.prepay.import-data");
    }

    public function import_prepay_store(Request $request){
        // Validate the uploaded file
        $request->validate([
            'file_to_upload' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $temp_path = $request->file('file_to_upload')->store('temp');

        try {
			Excel::import(new PrepaysImport, $temp_path);

            Storage::delete($temp_path);

			return redirect(route("employee-index"))->with('successImportExcel', 'Berhasil membaca file Excel dan menambahkan data kasbon pegawai.');
		}

		catch (Exception $e){
            Storage::delete($temp_path);

            throw $e;

			// return back()->with('failedImportExcel', "Gagal membaca dan menambahkan produk dari file Excel, harap perhatikan format yang telah ditentukan dan silakan coba kembali.");
		}
    }

    public function rollback() {
        $today = Carbon::today();
        $prev_end_period = $today->copy()->previous(Carbon::FRIDAY)->startOfDay();
        $prev_start_period = $prev_end_period->copy()->previous(Carbon::SATURDAY)->startOfDay();

        // Use transaction for safety
        DB::transaction(function() use ($prev_start_period, $prev_end_period) {
            $prepay_cuts = PrepayCut::where('start_period', $prev_start_period)
                ->where('end_period', $prev_end_period)
                ->get();

            foreach ($prepay_cuts as $ppc) {
                $cut_amount = $ppc->cut_amount;
                $prepay = $ppc->prepay;

                // Restore prepay current amount by adding back cut amount
                $prepay->curr_amount += $cut_amount;

                // Optionally re-enable auto_cut if it was disabled due to zero balance
                if ($prepay->enable_auto_cut === 'no' && $ppc->remaining_amount === 0) {
                    $prepay->enable_auto_cut = 'yes';
                }
                $prepay->save();

                // Delete the PrepayCut entry to fully rollback the cut
                $ppc->delete();
            }
        });

        return 'Pemotongan kasbon di periode terakhir telah dibatalkan';
    }

    public function check_prepays(){
        $today = Carbon::today();
        $prev_end_period = $today->copy()->previous(Carbon::FRIDAY)->startOfDay();
        $prev_start_period = $prev_end_period->copy()->previous(Carbon::SATURDAY)->startOfDay();

        $existing_prepay_cuts = PrepayCut::where('start_period', $prev_start_period)
                ->where('end_period', $prev_end_period)
                ->pluck('prepay_id')->toArray();

        $total_prepay_estimation = 0;
        $who = [];
        foreach(Prepay::whereNotIn('id', $existing_prepay_cuts)->where('prepay_date', '>=', $prev_start_period)->where('prepay_date', '<=', $prev_end_period)->where('curr_amount', '>', 0)->where('enable_auto_cut', 'yes')->whereHas('employee', function($query) {
            $query->where('status', 'active');
        })->get() as $ppay){
            $total_prepay_estimation += $ppay->cut_amount;
            array_push($who, $ppay->employee->nama);
        }

        return [$total_prepay_estimation, $who];
    }
}
