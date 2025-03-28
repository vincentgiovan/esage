<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Exception;
use Carbon\Carbon;
use App\Models\Prepay;
use App\Models\Employee;
use App\Models\PrepayCut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PrepayController extends Controller
{
    public function index($emp_id){
        return view('pages.prepay.index', [
            'prepays' => Prepay::where('employee_id', $emp_id)->orderBy('prepay_date', 'desc')->paginate(30),
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

        $prepay->update($validated_data);

        return redirect(route('prepay-index', $emp_id))->with('successEditPrepay', 'Berhasil memperbaharui data kasbon untuk pegawai ini.');
    }

    public function destroy($emp_id, $ppay_id){
        $prepay = Prepay::find($ppay_id);
        $prepay->delete();

        return redirect(route('prepay-index', $emp_id))->with('successDeletePrepay', 'Berhasil menghapus data kasbon dari pegawai ini.');
    }

    // For simulation purpose only
    public function generate() {
        $prepays = Prepay::where('curr_amount', '>', 0)
            ->where('enable_auto_cut', 'yes')
            ->orderBy('employee_id')
            ->get();

        foreach ($prepays as $ppay) {
            $last_generated_ppay_cut = PrepayCut::where('prepay_id', $ppay->id)
                ->latest()
                ->first();

            if ($last_generated_ppay_cut) {
                $prev_end_period = Carbon::parse($last_generated_ppay_cut->end_period);

                PrepayCut::create([
                    'prepay_id' => $last_generated_ppay_cut->prepay_id,
                    'start_period' => $prev_end_period->copy()->addDay(),
                    'end_period' => $prev_end_period->copy()->addDays(6),
                    'amount' => $ppay->cut_amount,
                ]);
            } else {
                // Generate missing periods from the prepay's created date to today
                $prepay_init_date = Carbon::parse($ppay->prepay_date);
                $today = Carbon::today();

                // ✅ Ensure the first start_period is **always a future Saturday**
                if ($prepay_init_date->isSaturday()) {
                    $start_period = $prepay_init_date->copy(); // Start on this Saturday
                } else {
                    $start_period = $prepay_init_date->copy()->next(Carbon::SATURDAY);
                }

                $end_period = $start_period->copy()->addDays(6); // Ends on Friday

                while ($end_period->lte($today)) {
                    $attendances = Attendance::where('employee_id', $ppay->employee_id)
                        ->whereBetween('attendance_date', [$start_period, $end_period])
                        ->get();

                    $salary_in_the_period = 0;
                    foreach ($attendances as $atd) {
                        $salary_in_the_period +=
                            ($atd->normal * $atd->employee->pokok) +
                            ($atd->jam_lembur * $atd->employee->lembur) +
                            ($atd->index_lembur_panjang * $atd->employee->lembur_panjang) +
                            $atd->performa;
                    }

                    if ($ppay->curr_amount < $ppay->cut_amount) {
                        break; // Stop generating if there's not enough balance
                    }

                    if ($salary_in_the_period < $ppay->cut_amount) {
                        // Skip this period, but correctly advance to the next week
                        $start_period = $end_period->copy()->addDay();
                        $end_period = $start_period->copy()->addDays(6);
                        continue;
                    }

                    PrepayCut::create([
                        'prepay_id' => $ppay->id,
                        'start_period' => $start_period,
                        'end_period' => $end_period,
                        'amount' => $ppay->cut_amount,
                    ]);

                    $ppay->curr_amount -= $ppay->cut_amount;
                    $ppay->save();

                    // ✅ Correctly advance to the next week
                    $start_period = $end_period->copy()->addDay();
                    $end_period = $start_period->copy()->addDays(6);
                }
            }
        }

        return back()->with('successGeneratePrepays', 'Data kasbon berhasil di-generate!');
    }



}
