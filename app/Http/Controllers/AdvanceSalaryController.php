<?php

namespace App\Http\Controllers;

use App\Models\AdvanceSalary;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdvanceSalaryController extends Controller {
    public function create() {
        $employees = Employee::orderBy( 'name' )->get();

        return view( 'advance.create', compact( 'employees' ) );
    }

    public function store( Request $request ) {
        $request->validate( [
            'employee_id' => 'required|exists:employees,id',
            'amount'      => 'required|numeric|min:1',
            'month'       => 'required',
        ] );

        AdvanceSalary::create( [

            'employee_id' => $request->employee_id,
            'amount'      => $request->amount,
            'month'       => Carbon::parse( $request->month )->format( 'Y-m' ),
            'taken_date'  => now(),

        ] );

        return back()->with( 'success', 'Advance salary added successfully' );
    }

    public function index() {
        $advances = AdvanceSalary::with( 'employee' )
            ->latest()
            ->get();

        return view( 'advance.index', compact( 'advances' ) );
    }
}
