<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;

class ProjectController extends Controller{

    public function index(){
        return view("pages.project.index", [
            "projects" => Project::all()
        ]);
    }

    public function create(){
        return view("pages.project.create");
    }




    public function store(Request $request){
        $validatedData = $request->validate([
            "project_name" => "required|min:3",
            "location"=>"required",
            "PIC" => "required|min:3",
            "address" => "required"
        ]);


        // $user = User::where("name", session("logged_in_user"))->first();
        // $validatedData["user_id"] = $user->id;

        Project::create($validatedData);
        return redirect(route("project-index"))->with("successAddProject", "Project added successfully!");


    }
    public function edit($id){


        return view("pages.project.edit", [
            "project" => Project::where("id", $id)->first()
        ]);

    }
    public function update(Request $request, $id){
        $validatedData = $request->validate([
            "project_name" => "required|min:3",
            "location"=>"required",
            "PIC" => "required|min:3",
            "address" => "required"
        ]);
        Project::where("id", $id)->update($validatedData);
        return redirect(route("project-index"))->with("successEditProject", "Project editted successfully!");

    }
    public function destroy($id){
        Project::destroy("id", $id);
        return redirect(route("project-index"))->with("successDeleteProject", "Project deleted successfully!");
    }
}
