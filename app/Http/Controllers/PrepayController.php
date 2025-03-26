<?php

namespace App\Http\Controllers;

use App\Models\Prepay;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PrepayController extends Controller
{
    public function store(Request $request, $emp_id){
        $employee = Employee::find($emp_id);

        // Use Validator to handle manual validation checks
        $validator = Validator::make($request->all(), [
            "c_prepay_date" => "required",
            "c_amount_tambah" => "required|numeric|min:0",
            "c_amount_potong" => "required|numeric|min:0",
            "c_remark" => "nullable|string"
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return back()
                ->withErrors($validator) // Pass validation errors
                ->withInput()            // Preserve form input
                ->with('create_form_visible', true); // Set flag to keep the form visible
        }

        $validated_data = $validator->validated();

        try {
            DB::beginTransaction();

            Prepay::create([
                "prepay_date" => $validated_data["c_prepay_date"],
                "amount" => $validated_data["c_amount_potong"],
                "remark" => $validated_data["c_remark"],
                "employee_id" => $employee->id
            ]);

            $employee->kasbon = $employee->kasbon + $validated_data['c_amount_tambah'] - $validated_data["c_amount_potong"];
            $employee->save();

            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();

            throw $e;
        }

        return back()->with('successAddPrepay', 'Berhasil menambahkan data kasbon baru untuk pegawai ini.');
    }

    public function update(Request $request, $emp_id, $ppay_id){
        $validator = Validator::make($request->all(), [
            "e_prepay_date" => "required",
            "e_amount" => "required|numeric|min:0",
            "e_remark" => "nullable"
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return back()
                ->withErrors($validator) // Pass validation errors
                ->withInput()            // Preserve form input
                ->with('edit_form_visible', true) // Set flag to keep the form visible
                ->with('edit_form_id', $ppay_id);
        }

        $validated_data = $validator->validated();

        try {
            DB::beginTransaction();

            $employee = Employee::find($emp_id);
            $prepay = Prepay::find($ppay_id);

            $employee->kasbon += $prepay->amount;
            $employee->kasbon -= $validated_data["e_amount"];
            $employee->save();

            $prepay->update([
                "prepay_date" => $validated_data["e_prepay_date"],
                "amount" => $validated_data["e_amount"],
                "remark" => $validated_data["e_remark"]
            ]);

            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();

            throw $e;
        }

        return back()->with('successEditPrepay', 'Berhasil memperbaharui data kasbon untuk pegawai ini.');
    }

    public function destroy($emp_id, $ppay_id){
        try {
            DB::beginTransaction();

            $employee = Employee::find($emp_id);
            $prepay = Prepay::find($ppay_id);

            $employee->kasbon += $prepay->amount;
            $employee->save();

            $prepay->delete();

            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();

            throw $e;
        }

        return back()->with('successDeletePrepay', 'Berhasil menghapus data kasbon dari pegawai ini.');

    }
}
