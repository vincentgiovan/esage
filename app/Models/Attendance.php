<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(isset($filters['from']) && isset($filters['until']), function ($query) use ($filters) {
            return $query->where('attendance_date', '>=', $filters['from'])
                ->where('attendance_date', '<=', $filters['until']);
        });

        $query->when($filters['from'] ?? false, function ($query, $from) {
            return $query->where('attendance_date', '>=', $from);
        });

        $query->when($filters['until'] ?? false, function ($query, $until) {
            return $query->where('attendance_date', '<=', $until);
        });
    }
}
