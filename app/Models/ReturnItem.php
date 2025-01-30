<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function return_item_products(){
        return $this->hasMany(ReturnItemProduct::class);
    }

    public function return_item_images(){
        return $this->hasMany(ReturnItemImage::class);
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }
}
