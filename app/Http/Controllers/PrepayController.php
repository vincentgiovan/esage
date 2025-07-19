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

            $groupedAttendances = Attendance::where('attendance_date', '>=', $prev_start_period)->where('attendance_date', '<=', $prev_end_period)->get()->groupBy('employee_id');

            $prepaysInThisPeriod = Prepay::where('prepay_date', '>=', $prev_start_period)->where('prepay_date', '<=', $prev_end_period)->where('enable_auto_cut', 'yes')->where('curr_amount', '>', 0)->get()->groupBy('employee_id');

            foreach ($groupedAttendances as $employee_id => $attendances) {
                $employee = $attendances->first()->employee; // Get the employee details

                if ($employee->kalkulasi_gaji == "on") {
                    $total_salary = 0;

                    foreach ($attendances as $atd) {
                        $sub_normal = $atd->normal * $employee->pokok;
                        $sub_lembur = $atd->jam_lembur * $employee->lembur;
                        $sub_lembur_panjang = $atd->index_lembur_panjang * $employee->lembur_panjang;
                        $sub_performa = $atd->performa * $employee->performa;

                        $total_salary += $sub_normal + $sub_lembur + $sub_lembur_panjang + $sub_performa;
                    }

                    // To show the prepay cut result on the given period
                    if(isset($prepaysInThisPeriod[strval($employee_id)])){
                        foreach($prepaysInThisPeriod[strval($employee_id)] as $ppay){
                            if($total_salary > 0){
                                $cut_amount = min($ppay->cut_amount, $ppay->curr_amount);

                                // Calculate the remaining amount after the cut
                                $remaining_amount = $ppay->curr_amount - $cut_amount;

                                // Determine if auto_cut should be disabled
                                $enable_auto_cut = $remaining_amount <= 0 ? 'no' : 'yes';

                                // Create the PrepayCut record
                                PrepayCut::create([
                                    'prepay_id' => $ppay->id,
                                    'start_period' => $prev_start_period,
                                    'end_period' => $prev_end_period,
                                    'init_amount' => $ppay->curr_amount,
                                    'cut_amount' => $cut_amount,
                                    'remaining_amount' => $remaining_amount,
                                ]);

                                // Update the prepay with new current amount and auto_cut status
                                $ppay->update([
                                    'curr_amount' => $remaining_amount,
                                    'enable_auto_cut' => $enable_auto_cut,
                                ]);

                                $total_salary -= $cut_amount;
                            }
                        }
                    }
                }
            }

            // // Ambil semua kasbon yang masibh ada saldonya dan disetel auto potong
            // $prepays = Prepay::where('curr_amount', '>', 0)
            //     ->where('enable_auto_cut', 'yes')
            //     ->orderBy('employee_id')
            //     ->get();

            // foreach ($prepays as $ppay) {
            //     // Skip pemotongan kasbon kalau ga disetel auto potong atau kalo karyawannya ga aktif
            //     if($ppay->enable_auto_cut == 'no' || !$ppay->employee || $ppay->employee->status == 'passive'){
            //         continue;
            //     }

            //     // ===== MEKANISME POTONG KASBOBN ===== //
            //     // Calculate the cut amount safely (can't cut more than current amount)
            //     $cut_amount = min($ppay->cut_amount, $ppay->curr_amount);

            //     // Calculate the remaining amount after the cut
            //     $remaining_amount = $ppay->curr_amount - $cut_amount;

            //     // Determine if auto_cut should be disabled
            //     $enable_auto_cut = $remaining_amount <= 0 ? 'no' : 'yes';

            //     // Create the PrepayCut record
            //     PrepayCut::create([
            //         'prepay_id' => $ppay->id,
            //         'start_period' => $prev_start_period,
            //         'end_period' => $prev_end_period,
            //         'init_amount' => $ppay->curr_amount,
            //         'cut_amount' => $cut_amount,
            //         'remaining_amount' => $remaining_amount,
            //     ]);

            //     // Update the prepay with new current amount and auto_cut status
            //     $ppay->update([
            //         'curr_amount' => $remaining_amount,
            //         'enable_auto_cut' => $enable_auto_cut,
            //     ]);

            // }

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
