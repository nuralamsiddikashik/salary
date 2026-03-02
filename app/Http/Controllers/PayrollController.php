<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Loan;
use App\Models\Payroll;
use App\Models\SalaryPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller {

    public function create() {
        $employees = Employee::all();
        return view( 'payroll.generate', compact( 'employees' ) );
    }

    public function generate( Request $request ) {
        $request->validate( [
            'employee_id' => 'required|exists:employees,id',
            'month'       => 'required|date',
            'absent_days' => 'required|integer|min:0',
            'leave_days'  => 'nullable|integer|min:0',
        ] );

        DB::beginTransaction();

        try {

            $employee = Employee::lockForUpdate()->findOrFail( $request->employee_id );

            $month = Carbon::parse( $request->month )->format( 'Y-m' );

            if ( Payroll::where( 'employee_id', $employee->id )
                ->where( 'month', $month )
                ->exists() ) {

                DB::rollBack();
                return back()->with( 'error', 'Salary already generated for this month' );
            }

            $currentYear = Carbon::parse( $month )->year;

            if ( $employee->leave_year != $currentYear ) {
                $employee->used_leave = 0;
                $employee->leave_year = $currentYear;
                $employee->save();
            }

            $absentDays = (int) $request->absent_days;
            $leaveDays  = (int) ( $request->leave_days ?? 0 );

            $daysInMonth = Carbon::parse( $month . '-01' )->daysInMonth;

            if ( $absentDays > $daysInMonth ) {
                DB::rollBack();
                return back()->with( 'error', 'Invalid absent days' );
            }

            if ( $leaveDays > $absentDays ) {
                DB::rollBack();
                return back()->with( 'error', 'Leave cannot exceed absent days' );
            }

            if ( $leaveDays > $employee->remaining_leave ) {
                DB::rollBack();
                return back()->with( 'error', 'Not enough leave balance' );
            }

            $salaryCutDays = $absentDays - $leaveDays;

            $dailySalary  = round( $employee->total_salary / $daysInMonth, 2 );
            $absentAmount = round( $dailySalary * $salaryCutDays, 2 );

            $employee->used_leave += $leaveDays;
            $employee->save();

            $loan = $employee->loans()
                ->where( 'remaining_amount', '>', 0 )
                ->lockForUpdate()
                ->first();

            $loanDeduction = 0;
            $loanId        = null;

            if ( $loan ) {
                $loanDeduction = min( $loan->monthly_deduction, $loan->remaining_amount );
                $loan->remaining_amount -= $loanDeduction;
                $loan->save();
                $loanId = $loan->id;
            }

            $netPayable = round(
                $employee->total_salary
                 - $absentAmount
                 - $loanDeduction,
                2
            );

            Payroll::create( [
                'employee_id'     => $employee->id,
                'month'           => $month,
                'absent_days'     => $absentDays,
                'leave_used'      => $leaveDays,
                'salary_cut_days' => $salaryCutDays,
                'absent_amount'   => $absentAmount,
                'loan_id'         => $loanId,
                'loan_deduction'  => $loanDeduction,
                'net_payable'     => $netPayable,
                'paid_amount'     => 0,
                'status'          => 'generated',
            ] );

            DB::commit();

            return back()->with( 'success', 'Salary Generated Successfully' );

        } catch ( \Exception $e ) {

            DB::rollBack();
            return back()->with( 'error', 'Something went wrong' );
        }
    }

    public function destroy( $id ) {
        DB::beginTransaction();

        try {

            $payroll = Payroll::lockForUpdate()->findOrFail( $id );

            // যদি already fully paid হয় delete allow না
            if ( $payroll->status === 'paid_full' ) {
                DB::rollBack();
                return back()->with( 'error', 'Cannot delete fully paid salary' );
            }

            // 🔹 Reverse Leave
            $employee = Employee::lockForUpdate()
                ->find( $payroll->employee_id );

            if ( $payroll->leave_used > 0 ) {
                $employee->used_leave -= $payroll->leave_used;
                $employee->save();
            }

            // 🔹 Reverse Loan (MAIN FIX)
            if ( $payroll->loan_id && $payroll->loan_deduction > 0 ) {

                $loan = Loan::lockForUpdate()
                    ->find( $payroll->loan_id );

                if ( $loan ) {
                    $loan->remaining_amount += $payroll->loan_deduction;
                    $loan->save();
                }
            }

            // 🔹 Delete Salary Payments (if any)
            SalaryPayment::where( 'payroll_id', $payroll->id )->delete();

            $payroll->delete();

            DB::commit();

            return back()->with( 'success', 'Salary deleted & Loan reversed successfully' );

        } catch ( \Exception $e ) {

            DB::rollBack();
            return back()->with( 'error', $e->getMessage() );
        }
    }
}