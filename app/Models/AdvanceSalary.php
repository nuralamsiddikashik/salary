<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class AdvanceSalary extends Model {
    protected $fillable = [
        'employee_id',
        'month',
        'amount',
        'taken_date',
    ];

    public function employee() {
        return $this->belongsTo( Employee::class );
    }
}