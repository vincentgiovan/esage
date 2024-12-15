<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends FetchUnarchivedData
{
    use HasFactory;
    protected $guarded = ["id"];

    public function purchases(){
        return $this->hasMany(Purchase::class);
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters["search"]?? false, function($query, $search) {
            return $query->where(function($query) use($search) {
                $query->where("partner_name", "like", "%". $search. "%");
            });
        });
    }
}
