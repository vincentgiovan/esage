<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prepay extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(isset($filters['from']) && isset($filters['until']), function ($query) use ($filters) {
            return $query->where('prepay_date', '>=', $filters['from'])
                ->where('prepay_date', '<=', $filters['until']);
        });

        $query->when($filters['from'] ?? false, function ($query, $from) {
            return $query->where('prepay_date', '>=', $from);
        });

        $query->when($filters['until'] ?? false, function ($query, $until) {
            return $query->where('prepay_date', '<=', $until);
        });

        $query->when($filters['employee'] ?? false, function($query, $employee){
            return $query->whereHas('employee', function($query) use ($employee){
                return $query->where('nama', 'like', '%' . $employee . '%');
            });
        });
    }
}
