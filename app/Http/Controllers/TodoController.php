<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function add_todo(Request $request){
        $request->validate(["new_task" => "required"]);

        Todo::create([
            "user_id" => Auth::user()->id,
            "task" => $request->new_task,
            "status" => "undone"
        ]);

        return redirect("/dashboard");
    }

    public function save_todo(Request $request){
        foreach(Todo::where("user_id", Auth::user()->id)->get() as $i => $todo){
            $new_status = ($request->checkboxes[$i] == "on")? "done" : "undone";
            Todo::where("id", $todo->id)->update(["status" => $new_status]);
        }

        return redirect("/dashboard");
    }
}