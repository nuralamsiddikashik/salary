<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model {
    protected $fillable = [
        'employee_id',
        'loan_amount',
        'monthly_deduction',
        'remaining_amount',
    ];

    public function employee() {
        return $this->belongsTo( Employee::class );
    }
}