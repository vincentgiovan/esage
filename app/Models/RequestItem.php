<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestItem extends FetchUnarchivedData
{
    use HasFactory;

    protected $guarded = ["id"];

    public function project(){
        return $this->belongsTo(Project::class);
    }
}
