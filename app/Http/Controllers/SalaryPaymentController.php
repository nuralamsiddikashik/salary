<?php
namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\SalaryPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryPaymentController extends Controller {

    public function pay( $payroll ) {
        DB::beginTransaction();

        try {

            $payroll = Payroll::with( 'employee' )
                ->lockForUpdate()
                ->findOrFail( $payroll );

            $grossSalary = $payroll->employee->total_salary;
            $netPayable  = $payroll->net_payable;

            $alreadyPaid = SalaryPayment::where( 'payroll_id', $payroll->id )
                ->sum( 'paid_amount' );

            if ( $alreadyPaid >= $netPayable ) {
                DB::rollBack();
                return back()->with( 'error', 'Already Fully Paid' );
            }

            /*
            |-----------------------------------
            | FIRST HALF
            |-----------------------------------
             */

            if ( $alreadyPaid == 0 ) {

                $firstHalf = round( $grossSalary / 2 );

                SalaryPayment::create( [
                    'employee_id'  => $payroll->employee_id,
                    'payroll_id'   => $payroll->id,
                    'paid_amount'  => $firstHalf,
                    'payment_type' => 'first_half',
                    'payment_date' => now(),
                ] );

                $payroll->update( [
                    'status' => 'paid_partial',
                ] );

                DB::commit();

                return back()->with( 'success', 'First Half Paid' );
            }

            /*
            |-----------------------------------
            | FINAL HALF
            |-----------------------------------
             */

            $finalAmount = max( 0, $netPayable - $alreadyPaid );

            SalaryPayment::create( [
                'employee_id'  => $payroll->employee_id,
                'payroll_id'   => $payroll->id,
                'paid_amount'  => $finalAmount,
                'payment_type' => 'final_half',
                'payment_date' => now(),
            ] );

            $payroll->update( [
                'status' => 'paid_full',
            ] );

            DB::commit();

            return back()->with( 'success', 'Final Payment Completed' );

        } catch ( \Exception $e ) {

            DB::rollBack();
            return back()->with( 'error', $e->getMessage() );
        }
    }

    public function bulkPay( Request $request ) {
        $request->validate( [
            'payroll_ids'  => 'required|array',
            'payment_type' => 'required|in:first_half,final_half',
        ] );

        DB::beginTransaction();

        try {

            foreach ( $request->payroll_ids as $payrollId ) {

                $payroll = Payroll::with( 'employee' )
                    ->lockForUpdate()
                    ->findOrFail( $payrollId );

                $grossSalary = $payroll->employee->total_salary;
                $netPayable  = $payroll->net_payable;

                $alreadyPaid = SalaryPayment::where( 'payroll_id', $payroll->id )
                    ->sum( 'paid_amount' );

                /*
                |--------------------------------------------------------------------------
                | FIRST HALF (No deduction)
                |--------------------------------------------------------------------------
                 */
                if ( $request->payment_type === 'first_half' ) {

                    if ( $alreadyPaid > 0 ) {
                        continue;
                    }

                    $amount = round( $grossSalary / 2 );

                    SalaryPayment::create( [
                        'employee_id'  => $payroll->employee_id,
                        'payroll_id'   => $payroll->id,
                        'paid_amount'  => $amount,
                        'payment_type' => 'first_half',
                        'payment_date' => now(),
                    ] );

                    $payroll->update( [
                        'status' => 'paid_partial',
                    ] );
                }

                /*
                |--------------------------------------------------------------------------
                | FINAL HALF (Apply deduction)
                |--------------------------------------------------------------------------
                 */
                if ( $request->payment_type === 'final_half' ) {

                    if ( $alreadyPaid >= $netPayable ) {
                        continue;
                    }

                    $amount = $netPayable - $alreadyPaid;

                    if ( $amount < 0 ) {
                        $amount = 0;
                    }

                    SalaryPayment::create( [
                        'employee_id'  => $payroll->employee_id,
                        'payroll_id'   => $payroll->id,
                        'paid_amount'  => $amount,
                        'payment_type' => 'final_half',
                        'payment_date' => now(),
                    ] );

                    $payroll->update( [
                        'status' => 'paid_full',
                    ] );
                }
            }

            DB::commit();

            return back()->with( 'success', 'Bulk payment completed' );

        } catch ( \Exception $e ) {

            DB::rollBack();

            return back()->with( 'error', $e->getMessage() );
        }
    }

}