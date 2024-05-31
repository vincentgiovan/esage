<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ["id"];
    public function delivery_orders(){
        return $this->hasMany(DeliveryOrder::class);
    }
    public function purchases(){
        return $this->hasMany(Purchase::class);
    }
}
