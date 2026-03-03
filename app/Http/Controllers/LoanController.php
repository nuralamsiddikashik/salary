<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller {
    // Show Loan Create Form
    public function create() {
        $employees = Employee::all();
        return view( 'loan.create', compact( 'employees' ) );
    }

    /**
     * make a loan and 2nd time loan
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function store( Request $request ) {
    //     $request->validate( [
    //         'employee_id'       => 'required|exists:employees,id',
    //         'loan_amount'       => 'required|numeric|min:1',
    //         'monthly_deduction' => 'required|numeric|min:1',
    //     ] );

    //     Loan::create( [
    //         'employee_id'       => $request->employee_id,
    //         'loan_amount'       => $request->loan_amount,
    //         'monthly_deduction' => $request->monthly_deduction,
    //         'remaining_amount'  => $request->loan_amount,
    //     ] );

    //     return back()->with( 'success', 'Loan Created Successfully' );
    // }

    public function store( Request $request ) {
        $request->validate( [
            'employee_id'       => 'required|exists:employees,id',
            'loan_amount'       => 'required|numeric|min:1',
            'monthly_deduction' => 'required|numeric|min:1',
        ] );

        DB::beginTransaction();

        try {

            // 🔹 Check if employee already has active loan
            $existingLoan = Loan::where( 'employee_id', $request->employee_id )
                ->where( 'remaining_amount', '>', 0 )
                ->lockForUpdate()
                ->first();

            if ( $existingLoan ) {

                // ✅ Add new amount to remaining only
                $existingLoan->remaining_amount += $request->loan_amount;

                // ❗ monthly deduction will NOT change
                $existingLoan->save();

            } else {

                // ✅ Create fresh loan
                Loan::create( [
                    'employee_id'       => $request->employee_id,
                    'loan_amount'       => $request->loan_amount,
                    'monthly_deduction' => $request->monthly_deduction,
                    'remaining_amount'  => $request->loan_amount,
                ] );
            }

            DB::commit();

            return back()->with( 'success', 'Loan Processed Successfully' );

        } catch ( \Exception $e ) {

            DB::rollBack();
            return back()->with( 'error', 'Something went wrong' );
        }
    }

}