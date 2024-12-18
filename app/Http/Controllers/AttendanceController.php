<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(){
        $attendances = Attendance::with('project')
            ->orderBy('attendance_date', 'asc')
            ->orderBy(Project::select('project_name')
                ->whereColumn('id', 'attendances.project_id')
                ->limit(1), 'asc')
            ->get();

        $subtotals = [];

        foreach($attendances as $atd){
            if($atd->employee->kalkulasi_gaji == "on"){
                $sub_normal = $atd->normal * $atd->employee->pokok;
                $sub_lembur = $atd->jam_lembur * $atd->employee->lembur;
                $sub_lembur_panjang = $atd->index_lembur_panjang * $atd->employee->lembur_panjang;
                $sub_performa = $atd->index_performa * $atd->employee->performa;

                $subtotal = $sub_normal + $sub_lembur + $sub_lembur_panjang + $sub_performa;
                array_push($subtotals, $subtotal);
            }
            else {
                array_push($subtotals, 'N/A');
            }
        }

        return view("pages.attendance.index", [
            "attendances" => $attendances,
            "subtotals" => $subtotals,
            "projects" => Project::where('archived', 0)->get()
        ]);
    }

    public function show($id){
        return view("pages.attendance.show", [
            "attendance" => Attendance::find($id)
        ]);
    }

    public function create_admin(Request $request){
        return view("pages.attendance.create-admin", [
            "project" => Project::find($request->query('project'))
        ]);
    }

    public function store_admin(Request $request){
        try {
            DB::beginTransaction();

            foreach($request->employee as $remp){
                $employee = Employee::find($remp);

                for($j = 0; $j < 7; $j++){
                    $atd_start_date = Carbon::parse($request->start_date);

                    $start_work = $request->start_time[$remp][$j] ? $request->start_time[$remp][$j] . ':00' : 'Off';
                    $end_work = $request->end_time[$remp][$j] ? $request->end_time[$remp][$j] . ':00' : 'Off';
                    $performa = $request->performa[$remp][$j] ?? 0;

                    if($end_work != 'Off'){
                        $status = 'Normal';
                        $jamnormal = 0;
                        $jamlembur = 0;

                        // Weekends
                        if($j == 0 || $j == 1){
                            if($end_work > '16:29:00' && $end_work <= '23:59:00'){
                                $status = 'Lembur';
                            }
                            else if($end_work >= '00:00:00' && $end_work < '08:00:00'){
                                $status = 'Lembur Panjang';
                            }
                        }
                        // Weekdays
                        else {
                            if($end_work > '17:29:00' && $end_work <= '23:59:00'){
                                $status = 'Lembur';
                            }
                            else if($end_work >= '00:00:00' && $end_work <= '00:59:00'){
                                $status = 'Lembur';
                            }
                            else if($end_work >= '01:00:00' && $end_work < '08:00:00'){
                                $status = 'Lembur Panjang';
                            }
                        }

                        // COUNTING NORMAL WORK HOUR
                        $carbonStartN = Carbon::createFromFormat('H:i:s', $start_work);
                        $carbonEndN = Carbon::createFromFormat('H:i:s', $end_work);

                        if ($carbonEndN->lessThan($carbonStartN)) {
                            $carbonEndN->addDay();
                        }

                        $minutesDifferenceN = $carbonStartN->diffInMinutes($carbonEndN);
                        $roundedMinutesN = floor($minutesDifferenceN / 60) * 60;
                        $jamnormal = min($roundedMinutesN / 60, ($j == 0 || $j == 1)? 8 : 9);

                        if($status != 'Normal'){
                            // COUNTING OVERTIME WORK HOUR
                            $carbonStartOT = Carbon::createFromFormat('H:i:s', ($j == 0 || $j == 1)? '16:00:00' : '17:00:00');
                            $carbonEndOT = Carbon::createFromFormat('H:i:s', $end_work);

                            if ($carbonEndOT->lessThan($carbonStartOT)) {
                                $carbonEndOT->addDay();
                            }

                            $minutesDifferenceOT = $carbonStartOT->diffInMinutes($carbonEndOT);
                            $roundedMinutesOT = round($minutesDifferenceOT / 30) * 30;
                            $jamlembur = $roundedMinutesOT / 60;

                            if($status == 'Lembur Panjang'){
                                $jamlembur -= 8;
                            }
                        }

                        Attendance::create([
                            "attendance_date" => $atd_start_date->addDays($j),
                            "employee_id" => $employee->id,
                            "project_id" => Project::find($request->project)->id,
                            "jam_masuk" => $start_work,
                            "jam_keluar" => $end_work,
                            "normal" => $jamnormal,
                            "jam_lembur" => $jamlembur,
                            "index_lembur_panjang" => ($status == 'Lembur Panjang')? 1 : 0,
                            "performa" => $performa,
                            "remark" => $request->remark
                        ]);
                    }
                }
            }

            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();

            throw $e;
        }

        return redirect(route('attendance-index'))->with('successCreateAttendance', 'Attendances data created successfully!');
    }

    public function index_self(){
        $existing_data = Attendance::where("attendance_date", Carbon::parse(now())->format('Y-m-d'))->where('employee_id', Auth::user()->employee_data->id)->get();

        return view("pages.attendance.index-self", [
            "existing_attendances" => $existing_data,
            "assigned_projects" => Auth::user()->employee_data->projects
        ]);
    }

    public function check_in($project_id){
        $project = Project::find($project_id);

        $existing_data = Attendance::where("attendance_date", Carbon::parse(now())->format('Y-m-d'))->where('employee_id', Auth::user()->employee_data->id)->where('project_id', $project->id)->first();

        if($existing_data){
            return back()->with('alreadyCheckIn', 'Anda sudah melakukan check in untuk proyek ini.');
        }
        else {
            return view("pages.attendance.checkin-self", [
                "project" => $project,
            ]);
        }
    }

    public function check_in_store(Request $request, $project_id){
        $validatedData = $request->validate([
            'check_in_time' => 'required',
            'evidence' => 'required|file|mimetypes:video/webm',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        if($request->file("evidence")){
            $validatedData['evidence_path'] = $request->file('evidence')->store('videos');
            unset($validatedData["evidence"]);
        }

        $project = Project::find($project_id);

        Attendance::create([
            "attendance_date" => Carbon::parse(Carbon::now())->format('Y-m-d'),
            "employee_id" => Auth::user()->employee_data->id,
            "project_id" => $project->id,
            "jam_masuk" => substr($validatedData["check_in_time"], 0, 5) . ':00',
            "bukti_masuk" => $validatedData["evidence_path"],
            "latitude_masuk" => $validatedData["latitude"],
            "longitude_masuk" => $validatedData["longitude"]
        ]);

        return redirect(route('attendance-self-index'))->with('successCheckInSelfAttendance', 'Berhasil melakukan check in pada presensi mandiri.');
    }

    public function check_out($project_id){
        $project = Project::find($project_id);

        $existing_data = Attendance::where("attendance_date", Carbon::parse(now())->format('Y-m-d'))->where('employee_id', Auth::user()->employee_data->id)->where('project_id', $project->id)->first();

        if($existing_data->jam_keluar){
            return back()->with('alreadyCheckOut', 'Anda sudah melakukan check out untuk proyek ini.');
        }
        else {
            return view("pages.attendance.checkout-self", [
                "project" => $project,
            ]);
        }
    }

    public function check_out_store(Request $request, $project_id){
        $validatedData = $request->validate([
            'check_out_time' => 'required',
            'evidence' => 'required|file|mimetypes:video/webm',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        if($request->file("evidence")){
            $validatedData['evidence_path'] = $request->file('evidence')->store('videos');
            unset($validatedData["evidence"]);
        }

        $project = Project::find($project_id);

        Attendance::where("attendance_date", Carbon::parse(now())->format('Y-m-d'))->where('employee_id', Auth::user()->employee_data->id)->where('project_id', $project->id)->update([
            "jam_keluar" => substr($validatedData["check_out_time"], 0, 5) . ':00',
            "bukti_keluar" => $validatedData["evidence_path"],
            "latitude_keluar" => $validatedData["latitude"],
            "longitude_keluar" => $validatedData["longitude"]
        ]);

        return redirect(route('attendance-self-index'))->with('successCheckInSelfAttendance', 'Berhasil melakukan check out pada presensi mandiri.');
    }

    public function edit($id){
        return view("pages.attendance.edit", [
            "attendance" => Attendance::find($id),
            "projects" => Project::where('archived', 0)->get(),
            "employees" => Employee::where('archived', 0)->get()
        ]);
    }

    public function update(Request $request, $id){
        $validatedData = $request->validate([
            "attendance_date" => "required",
            "employee_id" => "required",
            "project_id" => "required",
            "normal" => "required|numeric|min:0",
            "jam_lembur" => "required|numeric|min:0",
            "index_lembur_panjang" => "required|numeric|min:0",
            "performa" => "required|numeric|min:0",
            "remark" => "nullable",
            "jam_masuk" => "required",
            "jam_keluar" => "required"
        ]);

        Attendance::find($id)->update($validatedData);

        return redirect(route("attendance-index"))->with("successEditAttendance", "New attendance edited sucessfully!");
    }

    public function destroy($id){
        Attendance::find($id)->update(["archived" => 1]);

        return redirect(route("attendance-index"))->with("successDeleteAttendance", "New attendance deleted sucessfully!");
    }
}
