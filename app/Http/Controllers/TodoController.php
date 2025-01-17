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
        $all_todos = Todo::where("user_id", Auth::user()->id)->get();
        $i = $all_todos->count() - 1;
        foreach($all_todos as $todo){
            $new_status = ($request->checkboxes[$i] == "on")? "done" : "undone";
            Todo::find($todo->id)->update(["status" => $new_status]);

            $i--;
        }

        return redirect("/dashboard");
    }
}
