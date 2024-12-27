<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(isset($filters['from']) && isset($filters['until']), function ($query) use ($filters) {
            return $query->where(function ($query) use ($filters) {
                $query->whereBetween('start_period', [$filters['from'], $filters['until']])
                    ->orWhereBetween('end_period', [$filters['from'], $filters['until']])
                    ->orWhere(function ($query) use ($filters) {
                        $query->where('start_period', '<=', $filters['from'])
                                ->where('end_period', '>=', $filters['until']);
                    });
            });
        });

        $query->when($filters['from'] ?? false, function ($query, $from) {
            return $query->where('start_period', '>=', $from);
        });

        $query->when($filters['until'] ?? false, function ($query, $until) {
            return $query->where('end_period', '<=', $until);
        });
    }

}
