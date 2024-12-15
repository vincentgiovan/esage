<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends FetchUnarchivedData
{
    use HasFactory;

    protected $guarded = ["id"];

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }

    public function salaries(){
        return $this->hasMany(Salary::class);
    }

    public function projects(){
        return $this->belongsToMany(Project::class, "employee_projects");
    }
}
