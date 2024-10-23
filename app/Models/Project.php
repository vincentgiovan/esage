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

    public function scopeFilter($query, array $filters){
        $query->when($filters["search"]?? false, function($query, $search) {
            return $query->where(function($query) use($search) {
                $query->where("project_name", "like", "%". $search. "%");
            });
        });
    }

}
