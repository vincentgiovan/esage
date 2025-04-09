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
        
        // if (!empty($filters['from']) || !empty($filters['until'])) {
        //     $query->whereBetween('attendance_date', [
        //         $filters['from'] ?? '0000-01-01', // Use a default minimum date if `from` is empty
        //         $filters['until'] ?? now()->toDateString() // Use today’s date if `until` is empty
        //     ]);
        // }

        $query->when($filters['employee'] ?? false, function($query, $employee){
            return $query->whereHas('employee', function($query) use ($employee){
                return $query->where('nama', 'like', '%' . $employee . '%');
            });
        });

        $query->when($filters['project'] ?? false, function($query, $project){
            return $query->whereHas('project', function($query) use ($project){
                return $query->where('project_name', 'like', '%' . $project . '%');
            });
        });
    }
}
