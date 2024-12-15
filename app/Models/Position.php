<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends FetchUnarchivedData
{
    use HasFactory;

    protected $guarded = ["id"];
}
