<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestItemProduct extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function request_item(){
        return $this->belongsTo(RequestItem::class);
    }
}
