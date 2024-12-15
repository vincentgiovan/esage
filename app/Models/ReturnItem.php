<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnItem extends FetchUnarchivedData
{
    use HasFactory;

    protected $guarded = ["id"];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function delivery_order_product(){
        return $this->belongsTo(DeliveryOrderProduct::class);
    }
}
