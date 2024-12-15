<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Speciality extends FetchUnarchivedData
{
    use HasFactory;

    protected $guarded = ["id"];
}
