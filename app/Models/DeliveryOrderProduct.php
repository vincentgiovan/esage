<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrderProduct extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function delivery_order(){
        return $this->belongsTo(DeliveryOrder::class);
    }

}
