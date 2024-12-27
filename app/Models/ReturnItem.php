<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }
}
