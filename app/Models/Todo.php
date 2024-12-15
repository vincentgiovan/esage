<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Todo extends FetchUnarchivedData
{
    use HasFactory;

    protected $guarded = ["id"];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
