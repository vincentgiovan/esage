<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrepayCut extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function prepay(){
        return $this->belongsTo(Prepay::class);
    }
}
