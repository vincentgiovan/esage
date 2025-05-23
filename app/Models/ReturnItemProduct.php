<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItemProduct extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function return_item(){
        return $this->belongsTo(ReturnItem::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters["project"]?? false, function($query, $project) {
            return $query->whereHas('return_item', function($query) use($project) {
                return $query->whereHas('project', function($query) use($project) {
                    $query->where("project_name", "like", "%". $project. "%");
                });
            });
        });
    }
}
