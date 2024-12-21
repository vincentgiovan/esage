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
            "start_period" => "required",
            "end_period" => "required",
            "amount" => "required|numeric|min:0|not_in:0",
            "remark" => "nullable|string"
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return back()
                ->withErrors($validator) // Pass validation errors
                ->withInput()            // Preserve form input
                ->with('form_visible', true); // Set flag to keep the form visible
        }

        $validated_data = $validator->validated();
        $validated_data["employee_id"] = $employee->id;

        Prepay::create($validated_data);

        return back()->with('successAddPrepay', 'Berhasil menambahkan data kasbon baru untuk pegawai ini.');
    }

    public function update(Request $request, $emp_id, $ppay_id){
        $validated_data = $request->validate([
            "start_period" => "required",
            "end_period" => "required",
            "amount" => "required|numeric|min:0|not_in:0",
            "remark" => "nullable"
        ]);

        Prepay::find($ppay_id)->update($validated_data);

        return back()->with('successEditPrepay', 'Berhasil memperbaharui data kasbon untuk pegawai ini.');
    }

    public function destroy($emp_id, $ppay_id){
        Prepay::find($ppay_id)->delete();

        return back()->with('successDeletePrepay', 'Berhasil menghapus data kasbon dari pegawai ini.');

    }
}
