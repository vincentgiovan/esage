<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends FetchUnarchivedData
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
}
