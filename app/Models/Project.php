<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function delivery_orders(){
        return $this->hasMany(DeliveryOrder::class);
    }

    public function employees(){
        return $this->belongsToMany(Employee::class, "employee_projects")->orderByRaw(
            'CASE
                WHEN jabatan LIKE "%Manage%" THEN 1
                WHEN jabatan = "Kepala Tukang" THEN 2
                WHEN jabatan = "Tukang" THEN 3
                WHEN jabatan = "1/2 Tukang" THEN 4
                WHEN jabatan = "Mandor" THEN 5
                WHEN jabatan = "Laden" THEN 6
                ELSE 7
            END'
        );
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters["search"]?? false, function($query, $search) {
            return $query->where(function($query) use($search) {
                $query->where("project_name", "like", "%". $search. "%");
            });
        });
    }

}
