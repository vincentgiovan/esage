<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function admin_index(){
        return view("pages.leave.index-admin", [
            "leaves" => Leave::orderByRaw("CASE WHEN approved = 'awaiting' THEN 0 ELSE 1 END")->orderBy('start_period', 'asc')->paginate(30)
        ]);
    }

    public function user_index(){
        if(Auth::user()->employee_data){
            return view("pages.leave.index-user", [
                'no_employee' => false,
                "leaves" => Leave::where('employee_id', Auth::user()->employee_data->id)->paginate(30)
            ]);
        }
        else {
            return view("pages.leave.index-user", [
                'no_employee' => true
            ]);
        }
    }

    public function user_propose(){
        return view('pages.leave.propose-user');
    }

    public function user_propose_store(Request $request){
        $validatedData = $request->validate([
            "start_period" => "required",
            "end_period" => "required",
            "remark" => "required"
        ]);

        $validatedData["employee_id"] = Auth::user()->employee_data->id;

        Leave::create($validatedData);

        return redirect(route('leave-user-index'))->with('successProposeLeave', 'Berhasil mengajukan cuti.');
    }

    public function admin_approve($id){
        Leave::find($id)->update(['approved' => 'yes']);

        return back()->with('successApproveLeave', 'Berhasil menyetujui pengajuan cuti karyawan.');
    }

    public function admin_reject($id){
        Leave::find($id)->update(['approved' => 'no']);

        return back()->with('successRejectLeave', 'Berhasil menolak pengajuan cuti karyawan.');
    }
}
