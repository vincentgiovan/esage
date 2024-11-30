<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index(){
        $salaries = Salary::all();
        $totals = [];

        foreach($salaries as $s){
            if($s->employee->kalkulasi_gaji == "on"){
                $total = 0;
                foreach($s->employee->attendances as $atd){
                    $sub_normal = $atd->normal * $atd->employee->pokok;
                    $sub_lembur = $atd->jam_lembur * $atd->employee->lembur;
                    $sub_lembur_panjang = $atd->index_lembur_panjang * $atd->employee->lembur_panjang;
                    $sub_performa = $atd->index_performa * $atd->employee->performa;

                    $total += $sub_normal + $sub_lembur + $sub_lembur_panjang + $sub_performa;
                }

                array_push($totals, $total);
            }
            else {
                array_push($totals, "N/A");
            }
        }

        return view("pages.salary.index", [
            "salaries" => $salaries,
            "totals" => $totals
        ]);
    }

    public function edit($id){
        return view("pages.salary.edit", [
            "salary" => Salary::find($id)
        ]);
    }

    public function update(Request $request, $id){
        Salary::find($id)->update(["keterangan" => $request->keterangan]);

        return redirect(route('salary-index'))->with("successEditSalary", "Salary edited successfully!");
    }
}
