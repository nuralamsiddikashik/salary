<?php

namespace Database\Factories;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollFactory extends Factory {
    public function definition(): array {
        $employee = Employee::inRandomOrder()->first();

        $month = now()->format( 'Y-m' );

        $daysInMonth = Carbon::parse( $month . '-01' )->daysInMonth;

        $absentDays = fake()->numberBetween( 0, 3 );
        $leaveDays  = fake()->numberBetween( 0, $absentDays );

        $salaryCutDays = $absentDays - $leaveDays;

        $dailySalary = $employee->total_salary / $daysInMonth;

        $absentAmount = $dailySalary * $salaryCutDays;

        $loanDeduction = fake()->randomElement( [0, 1000, 2000] );

        $advance = fake()->randomElement( [0, 500, 1000] );

        $netPayable = $employee->total_salary
             - $absentAmount
             - $loanDeduction
             - $advance;

        return [

            'employee_id'       => $employee->id,

            'month'             => $month,

            'absent_days'       => $absentDays,

            'leave_used'        => $leaveDays,

            'salary_cut_days'   => $salaryCutDays,

            'absent_amount'     => round( $absentAmount, 2 ),

            'loan_deduction'    => $loanDeduction,

            'advance_deduction' => $advance,

            'net_payable'       => round( $netPayable, 2 ),
        ];
    }
}