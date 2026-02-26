<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model {
    protected $fillable = [
        'employee_id',
        'month',
        'absent_days',
        'absent_amount',
        'loan_deduction',
        'net_payable',
    ];

    public function employee() {
        return $this->belongsTo( Employee::class );
    }

}