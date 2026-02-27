<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model {
    use HasFactory;
    protected $fillable = [
        'name',
        'join_date',
        'designation',
        'total_salary',
        'basic_salary',
        'house_rent',
        'medical',
        'conveyance',
        'total_leave',
        'used_leave',
        'leave_year',
    ];

    // ✅ Payroll Relation
    public function payrolls() {
        return $this->hasMany( \App\Models\Payroll::class );
    }

    // ✅ Loan Relation
    public function loans() {
        return $this->hasMany( \App\Models\Loan::class );
    }

    // Optional: Latest Payroll
    public function latestPayroll() {
        return $this->hasOne( \App\Models\Payroll::class )->latestOfMany();
    }

    // Remaining Leave Accessor
    public function getRemainingLeaveAttribute() {
        return $this->total_leave - $this->used_leave;
    }
}