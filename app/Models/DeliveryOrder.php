<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    use HasFactory;


    protected $guarded = ["id"];

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class, "delivery_order_products");
    }

    public function delivery_order_products(){
        return $this->hasMany(DeliveryOrderProduct::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters["search"] ?? false, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->whereHas('project', function ($query) use ($search) {
                    $query->where('project_name', 'like', '%' . $search . '%');
                })->orWhere("register", "like", "%" . $search . "%");
            });
        });
    }


}
