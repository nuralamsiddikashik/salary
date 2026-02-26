<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller {
    // 🔹 Show Generate Form (GET)
    public function create() {
        $employees = Employee::all();
        return view( 'payroll.generate', compact( 'employees' ) );
    }

    // 🔹 Store Salary (POST)
    public function generate( Request $request ) {
        $request->validate( [
            'employee_id' => 'required|exists:employees,id',
            'month'       => 'required',
            'absent_days' => 'nullable|integer|min:0',
        ] );

        $employee = Employee::findOrFail( $request->employee_id );

        $absentDays = $request->absent_days ?? 0;

        // Absent Calculation
        $dailySalary  = $employee->total_salary / 30;
        $absentAmount = $dailySalary * $absentDays;

        // Loan Deduction
        $loan = $employee->loans()
            ->where( 'remaining_amount', '>', 0 )
            ->first();

        $loanDeduction = 0;

        if ( $loan ) {
            $loanDeduction = min( $loan->monthly_deduction, $loan->remaining_amount );
            $loan->remaining_amount -= $loanDeduction;
            $loan->save();
        }

        // Net Salary
        $netPayable = $employee->total_salary
             - $absentAmount
             - $loanDeduction;

        Payroll::create( [
            'employee_id'    => $employee->id,
            'month'          => $request->month,
            'absent_days'    => $absentDays,
            'absent_amount'  => $absentAmount,
            'loan_deduction' => $loanDeduction,
            'net_payable'    => $netPayable,
        ] );

        return back()->with( 'success', 'Salary Generated Successfully' );
    }

}