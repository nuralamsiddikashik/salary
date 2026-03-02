<?php
namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\SalaryPayment;
use Illuminate\Support\Facades\DB;

class SalaryPaymentController extends Controller {
    // public function pay( $payrollId ) {
    //     $payroll = Payroll::with( 'employee', 'payments' )
    //         ->findOrFail( $payrollId );

    //     $totalSalary = $payroll->employee->total_salary;
    //     $netPayable  = $payroll->net_payable;

    //     $alreadyPaid = $payroll->payments->sum( 'paid_amount' );

    //     // 🔹 First Half Payment
    //     if ( $alreadyPaid == 0 ) {

    //         $firstHalf = $totalSalary / 2;

    //         SalaryPayment::create( [
    //             'employee_id'  => $payroll->employee_id,
    //             'payroll_id'   => $payroll->id,
    //             'paid_amount'  => $firstHalf,
    //             'payment_type' => 'first_half',
    //             'payment_date' => now(),
    //         ] );

    //         return back()->with( 'success', 'First Half Paid' );
    //     }

    //     // 🔹 Final Half Payment
    //     if ( $alreadyPaid < $netPayable ) {

    //         $remaining = $netPayable - $alreadyPaid;

    //         if ( $remaining <= 0 ) {
    //             return back()->with( 'error', 'No Remaining Payable' );
    //         }

    //         SalaryPayment::create( [
    //             'employee_id'  => $payroll->employee_id,
    //             'payroll_id'   => $payroll->id,
    //             'paid_amount'  => $remaining,
    //             'payment_type' => 'final_half',
    //             'payment_date' => now(),
    //         ] );

    //         return back()->with( 'success', 'Final Half Paid' );
    //     }

    //     return back()->with( 'error', 'Already Fully Paid' );
    // }

    public function pay( $payrollId ) {
        DB::beginTransaction();

        try {

            $payroll = Payroll::with( 'employee' )
                ->lockForUpdate()
                ->findOrFail( $payrollId );

            $totalSalary = $payroll->employee->total_salary;
            $netPayable  = $payroll->net_payable;

            $alreadyPaid = SalaryPayment::where( 'payroll_id', $payroll->id )
                ->sum( 'paid_amount' );

            if ( $alreadyPaid >= $netPayable ) {
                DB::rollBack();
                return back()->with( 'error', 'Already Fully Paid' );
            }

            // 🔹 FIRST HALF (Always 50% of Total Salary)
            if ( $alreadyPaid == 0 ) {

                $firstHalf = round( $totalSalary / 2, 2 );

                SalaryPayment::create( [
                    'employee_id'  => $payroll->employee_id,
                    'payroll_id'   => $payroll->id,
                    'paid_amount'  => $firstHalf,
                    'payment_type' => 'first_half',
                    'payment_date' => now(),
                ] );

                $payroll->update( ['status' => 'paid_partial'] );

                DB::commit();
                return back()->with( 'success', 'First Half Paid (50% of Total Salary)' );
            }

            // 🔹 FINAL PAYMENT (After Deduction)
            $remaining = $netPayable - $alreadyPaid;

            if ( $remaining < 0 ) {
                $remaining = 0; // safety
            }

            SalaryPayment::create( [
                'employee_id'  => $payroll->employee_id,
                'payroll_id'   => $payroll->id,
                'paid_amount'  => $remaining,
                'payment_type' => 'final_half',
                'payment_date' => now(),
            ] );

            $payroll->update( ['status' => 'paid_full'] );

            DB::commit();

            return back()->with( 'success', 'Final Payment Completed (After Deduction)' );

        } catch ( \Exception $e ) {

            DB::rollBack();
            return back()->with( 'error', $e->getMessage() );
        }
    }
}