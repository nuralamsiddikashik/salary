<?php

namespace App\Models;

use App\Models\SalaryPayment;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model {
    protected $fillable = [
        'employee_id',
        'month',
        'absent_days',
        'absent_amount',
        'loan_deduction',
        'net_payable',
        'leave_used',
        'salary_cut_days',
    ];

    public function employee() {
        return $this->belongsTo( Employee::class );
    }

    public function payments() {
        return $this->hasMany( SalaryPayment::class );
    }

}