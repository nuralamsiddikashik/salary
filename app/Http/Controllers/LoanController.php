<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller {
    // Show Loan Create Form
    public function create() {
        $employees = Employee::all();
        return view( 'loan.create', compact( 'employees' ) );
    }

    // Store Loan
    public function store( Request $request ) {
        $request->validate( [
            'employee_id'       => 'required|exists:employees,id',
            'loan_amount'       => 'required|numeric|min:1',
            'monthly_deduction' => 'required|numeric|min:1',
        ] );

        Loan::create( [
            'employee_id'       => $request->employee_id,
            'loan_amount'       => $request->loan_amount,
            'monthly_deduction' => $request->monthly_deduction,
            'remaining_amount'  => $request->loan_amount,
        ] );

        return back()->with( 'success', 'Loan Created Successfully' );
    }
}