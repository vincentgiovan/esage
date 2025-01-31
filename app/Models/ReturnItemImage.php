<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItemImage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function return_item(){
        return $this->belongsTo(ReturnItem::class);
    }
}
