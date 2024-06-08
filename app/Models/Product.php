<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function purchases(){
        return $this->belongsToMany(Purchase::class);
    }

    public function delivery_orders(){
        return $this->belongsToMany(DeliveryOrder::class, "delivery_order_products");
    }

    public function delivery_order_products(){
        return $this->hasMany(DeliveryOrderProduct::class);
    }

    public function purchase_products(){
        return $this->hasMany(PurchaseProduct::class);
    }

}
