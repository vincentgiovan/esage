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
}
