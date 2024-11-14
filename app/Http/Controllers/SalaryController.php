<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index(){
        return view("pages.salary.index", [
            "salaries" => Salary::all()
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
