<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller {
    // Employee List
    public function index() {
        $employees = Employee::with( 'latestPayroll' )
            ->latest()
            ->get();

        return view( 'employee.index', compact( 'employees' ) );
    }

    // Create Form
    public function create() {
        return view( 'employee.create' );
    }

    // Store Employee
    public function store( Request $request ) {
        $request->validate( [
            'name'         => 'required',
            'join_date'    => 'required|date',
            'designation'  => 'required',
            'total_salary' => 'required|numeric',
        ] );

        $total = $request->total_salary;

        // Salary Breakdown (Fixed)
        $basic      = $total * 0.50;
        $house      = $total * 0.30;
        $medical    = $total * 0.10;
        $conveyance = $total * 0.10;

        Employee::create( [
            'name'         => $request->name,
            'join_date'    => $request->join_date,
            'designation'  => $request->designation,
            'total_salary' => $total,
            'basic_salary' => $basic,
            'house_rent'   => $house,
            'medical'      => $medical,
            'conveyance'   => $conveyance,
        ] );

        return redirect()->route( 'employee.list' )
            ->with( 'success', 'Employee Created Successfully' );
    }
}