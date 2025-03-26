<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
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

    public function prepays(){
        return $this->hasMany(Prepay::class);
    }

    public function leaves(){
        return $this->hasMany(Leave::class);
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters["status"]?? false, function($query, $status) {
            return $query->where(function($query) use($status) {
                $query->where("status", $status);
            });
        });

        $query->when($filters["search"]?? false, function($query, $search) {
            return $query->where(function($query) use($search) {
                $query->where("nama", 'like', '%' . $search . '%');
            });
        });
    }
}
