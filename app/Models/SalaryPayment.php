<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model {
    protected $fillable = [
        'employee_id',
        'payroll_id',
        'paid_amount',
        'payment_type',
        'payment_date',
    ];

    public function employee() {
        return $this->belongsTo( Employee::class );
    }

    public function payroll() {
        return $this->belongsTo( Payroll::class );
    }
}