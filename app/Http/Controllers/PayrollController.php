<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PayrollController extends Controller {
    // 🔹 Show Generate Form (GET)
    public function create() {
        $employees = Employee::all();
        return view( 'payroll.generate', compact( 'employees' ) );
    }

    public function generate( Request $request ) {
        $request->validate( [
            'employee_id' => 'required|exists:employees,id',
            'month'       => 'required',
            'absent_days' => 'required|integer|min:0',
            'leave_days'  => 'nullable|integer|min:0',
        ] );

        $employee = Employee::findOrFail( $request->employee_id );

        $month = Carbon::parse( $request->month )->format( 'Y-m' );

        // 🔴 Prevent duplicate salary
        if ( Payroll::where( 'employee_id', $employee->id )
            ->where( 'month', $month )
            ->exists() ) {

            return back()->with( 'error', 'Salary already generated for this month' );
        }

        // 🔄 Yearly Leave Reset
        $currentYear = Carbon::parse( $month )->year;

        if ( $employee->leave_year != $currentYear ) {
            $employee->used_leave = 0;
            $employee->leave_year = $currentYear;
            $employee->save();
        }

        $absentDays = $request->absent_days;
        $leaveDays  = $request->leave_days ?? 0;

        // Get total days in month (28/29/30/31 auto)
        $daysInMonth = Carbon::parse( $month . '-01' )->daysInMonth;

        // Absent validation
        if ( $absentDays > $daysInMonth ) {
            return back()->with( 'error', 'Absent days cannot exceed total days of month' );
        }

        if ( $leaveDays > $absentDays ) {
            return back()->with( 'error', 'Leave cannot exceed absent days' );
        }

        if ( $leaveDays > $employee->remaining_leave ) {
            return back()->with( 'error', 'Not enough leave available' );
        }

        // Salary cut days
        $salaryCutDays = $absentDays - $leaveDays;

        // Calendar based salary calculation
        $dailySalary  = round( $employee->total_salary / $daysInMonth, 2 );
        $absentAmount = round( $dailySalary * $salaryCutDays, 2 );

        // Update leave usage
        $employee->used_leave += $leaveDays;
        $employee->save();

        // Loan deduction (optional)
        $loan = $employee->loans()
            ->where( 'remaining_amount', '>', 0 )
            ->first();

        $loanDeduction = 0;

        if ( $loan ) {
            $loanDeduction = min( $loan->monthly_deduction, $loan->remaining_amount );
            $loan->remaining_amount -= $loanDeduction;
            $loan->save();
        }

        $netPayable = $employee->total_salary
             - $absentAmount
             - $loanDeduction;

        Payroll::create( [
            'employee_id'     => $employee->id,
            'month'           => $month,
            'absent_days'     => $absentDays,
            'leave_used'      => $leaveDays,
            'salary_cut_days' => $salaryCutDays,
            'absent_amount'   => $absentAmount,
            'loan_deduction'  => $loanDeduction,
            'net_payable'     => $netPayable,
        ] );

        return back()->with( 'success', 'Salary Generated Successfully' );
    }
}