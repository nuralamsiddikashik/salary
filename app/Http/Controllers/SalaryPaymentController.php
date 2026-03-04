<?php
namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\SalaryPayment;
use Illuminate\Support\Facades\DB;

class SalaryPaymentController extends Controller {

    // public function pay( $payrollId ) {
    //     DB::beginTransaction();

    //     try {

    //         $payroll = Payroll::with( 'employee' )
    //             ->lockForUpdate()
    //             ->findOrFail( $payrollId );

    //         $totalSalary = $payroll->employee->total_salary;
    //         $netPayable  = $payroll->net_payable;

    //         $alreadyPaid = SalaryPayment::where( 'payroll_id', $payroll->id )
    //             ->sum( 'paid_amount' );

    //         if ( $alreadyPaid >= $netPayable ) {
    //             DB::rollBack();
    //             return back()->with( 'error', 'Already Fully Paid' );
    //         }

    //         // 🔹 FIRST HALF (Always 50% of Total Salary)
    //         if ( $alreadyPaid == 0 ) {

    //             $firstHalf = round( $totalSalary / 2, 2 );

    //             SalaryPayment::create( [
    //                 'employee_id'  => $payroll->employee_id,
    //                 'payroll_id'   => $payroll->id,
    //                 'paid_amount'  => $firstHalf,
    //                 'payment_type' => 'first_half',
    //                 'payment_date' => now(),
    //             ] );

    //             $payroll->update( ['status' => 'paid_partial'] );

    //             DB::commit();
    //             return back()->with( 'success', 'First Half Paid (50% of Total Salary)' );
    //         }

    //         // 🔹 FINAL PAYMENT (After Deduction)
    //         $remaining = $netPayable - $alreadyPaid;

    //         if ( $remaining < 0 ) {
    //             $remaining = 0; // safety
    //         }

    //         SalaryPayment::create( [
    //             'employee_id'  => $payroll->employee_id,
    //             'payroll_id'   => $payroll->id,
    //             'paid_amount'  => $remaining,
    //             'payment_type' => 'final_half',
    //             'payment_date' => now(),
    //         ] );

    //         $payroll->update( ['status' => 'paid_full'] );

    //         DB::commit();

    //         return back()->with( 'success', 'Final Payment Completed (After Deduction)' );

    //     } catch ( \Exception $e ) {

    //         DB::rollBack();
    //         return back()->with( 'error', $e->getMessage() );
    //     }
    // }

    // public function pay( $payrollId ) {
    //     DB::beginTransaction();

    //     try {

    //         $payroll = Payroll::with( 'employee' )
    //             ->lockForUpdate()
    //             ->findOrFail( $payrollId );

    //         $netPayable = $payroll->net_payable;

    //         $alreadyPaid = SalaryPayment::where( 'payroll_id', $payroll->id )
    //             ->sum( 'paid_amount' );

    //         // 🔴 Already fully paid
    //         if ( $alreadyPaid >= $netPayable ) {
    //             DB::rollBack();
    //             return back()->with( 'error', 'Already Fully Paid' );
    //         }

    //         $remaining = $netPayable - $alreadyPaid;

    //         // 🔹 FIRST HALF (50% of Net Payable)
    //         if ( $alreadyPaid == 0 ) {

    //             $amount = round( $netPayable / 2, 2 );
    //             $type   = 'first_half';
    //         }
    //         // 🔹 FINAL PAYMENT (Remaining Balance)
    //         else {

    //             $amount = $remaining;
    //             $type   = ( $remaining == $netPayable / 2 ) ? 'final_half' : 'full';
    //         }

    //         // 🟢 Prevent accidental overpayment
    //         if ( $amount > $remaining ) {
    //             $amount = $remaining;
    //         }

    //         SalaryPayment::create( [
    //             'employee_id'  => $payroll->employee_id,
    //             'payroll_id'   => $payroll->id,
    //             'paid_amount'  => $amount,
    //             'payment_type' => $type,
    //             'payment_date' => now(),
    //         ] );

    //         // 🔄 Update Payroll Status
    //         $newTotalPaid = $alreadyPaid + $amount;

    //         if ( $newTotalPaid >= $netPayable ) {
    //             $payroll->update( ['status' => 'paid_full'] );
    //         } else {
    //             $payroll->update( ['status' => 'paid_partial'] );
    //         }

    //         DB::commit();

    //         return back()->with( 'success', ucfirst( str_replace( '_', ' ', $type ) ) . ' Payment Successful' );

    //     } catch ( \Exception $e ) {

    //         DB::rollBack();
    //         return back()->with( 'error', $e->getMessage() );
    //     }
    // }

    public function pay( $payrollId ) {
        DB::beginTransaction();

        try {

            $payroll = Payroll::with( 'employee' )
                ->lockForUpdate()
                ->findOrFail( $payrollId );

            $totalSalary = $payroll->employee->total_salary; // Gross
            $netPayable  = $payroll->net_payable; // After deduction

            $alreadyPaid = SalaryPayment::where( 'payroll_id', $payroll->id )
                ->sum( 'paid_amount' );

            // 🔴 Already Fully Paid
            if ( $alreadyPaid >= $netPayable ) {
                DB::rollBack();
                return back()->with( 'error', 'Already Fully Paid' );
            }

            // 🔹 FIRST HALF (50% of TOTAL SALARY — No Deduction)
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
                return back()->with( 'success', 'First Half Paid (No Deduction Applied)' );
            }

            // 🔹 FINAL PAYMENT (Apply All Deductions Now)
            $finalAmount = $netPayable - $alreadyPaid;

            if ( $finalAmount < 0 ) {
                $finalAmount = 0;
            }

            SalaryPayment::create( [
                'employee_id'  => $payroll->employee_id,
                'payroll_id'   => $payroll->id,
                'paid_amount'  => $finalAmount,
                'payment_type' => 'final_half',
                'payment_date' => now(),
            ] );

            $payroll->update( ['status' => 'paid_full'] );

            DB::commit();

            return back()->with( 'success', 'Final Payment Completed (All Deductions Applied)' );

        } catch ( \Exception $e ) {

            DB::rollBack();
            return back()->with( 'error', $e->getMessage() );
        }
    }

}