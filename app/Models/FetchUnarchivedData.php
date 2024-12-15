<?php

namespace App\Models;

use App\Models\Scopes\ArchivedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FetchUnarchivedData extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new ArchivedScope);
    }
}
