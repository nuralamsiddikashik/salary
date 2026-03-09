<?php

namespace App\Models;

use App\Models\SalaryPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model {
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'loan_id',
        'month',
        'absent_days',
        'absent_amount',
        'loan_deduction',
        'net_payable',
        'leave_used',
        'salary_cut_days',
        'advance_deduction',
    ];

    public function employee() {
        return $this->belongsTo( Employee::class );
    }

    public function payments() {
        return $this->hasMany( SalaryPayment::class );
    }

}