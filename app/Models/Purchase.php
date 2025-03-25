<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function partner(){
        return $this->belongsTo(Partner::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class,"purchase_products");
    }

    public function purchase_products(){
        return $this->hasMany(PurchaseProduct::class);
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters["search"] ?? false, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->whereHas('partner', function ($query) use ($search) {
                    $query->where('partner_name', 'like', '%' . $search . '%');
                })->orWhere("register", "like", "%" . $search . "%");
            });
        });
    }
}
