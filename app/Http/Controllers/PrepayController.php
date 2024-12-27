<?php

namespace App\Http\Controllers;

use App\Models\Prepay;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PrepayController extends Controller
{
    public function store(Request $request, $emp_id){
        $employee = Employee::find($emp_id);

        // Use Validator to handle manual validation checks
        $validator = Validator::make($request->all(), [
            "c_start_period" => "required",
            "c_end_period" => "required",
            "c_amount" => "required|numeric|min:0|not_in:0",
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

        Prepay::create([
            "start_period" => $validated_data["c_start_period"],
            "end_period" => $validated_data["c_end_period"],
            "amount" => $validated_data["c_amount"],
            "remark" => $validated_data["c_remark"],
            "employee_id" => $employee->id
        ]);

        return back()->with('successAddPrepay', 'Berhasil menambahkan data kasbon baru untuk pegawai ini.');
    }

    public function update(Request $request, $emp_id, $ppay_id){
        $validator = Validator::make($request->all(), [
            "e_start_period" => "required",
            "e_end_period" => "required",
            "e_amount" => "required|numeric|min:0|not_in:0",
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

        Prepay::find($ppay_id)->update([
            "start_period" => $validated_data["e_start_period"],
            "end_period" => $validated_data["e_end_period"],
            "amount" => $validated_data["e_amount"],
            "remark" => $validated_data["e_remark"]
        ]);

        return back()->with('successEditPrepay', 'Berhasil memperbaharui data kasbon untuk pegawai ini.');
    }

    public function destroy($emp_id, $ppay_id){
        Prepay::find($ppay_id)->delete();

        return back()->with('successDeletePrepay', 'Berhasil menghapus data kasbon dari pegawai ini.');

    }
}
