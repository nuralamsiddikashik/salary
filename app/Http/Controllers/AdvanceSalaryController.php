<?php

namespace App\Http\Controllers;

use App\Models\AdvanceSalary;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvanceSalaryController extends Controller {

    public function index( Request $request ) {
        $month = $request->month;

        $advances = AdvanceSalary::with( 'employee' )
            ->when( $month, function ( $q ) use ( $month ) {
                $q->where( 'month', Carbon::parse( $month )->format( 'Y-m' ) );
            } )
            ->latest()
            ->get();

        $totalAdvance = $advances->sum( 'amount' );

        return view( 'advance.index', compact(
            'advances',
            'totalAdvance',
            'month'
        ) );
    }

    public function create() {
        $employees = Employee::orderBy( 'name' )->get();

        return view( 'advance.create', compact( 'employees' ) );
    }

    public function store( Request $request ) {
        $request->validate( [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'amount'      => ['required', 'numeric', 'min:1', 'max:1000000'],
            'month'       => ['required', 'date_format:Y-m'],
        ] );

        DB::beginTransaction();

        try {

            // 🔹 Normalize month
            $month = Carbon::createFromFormat( 'Y-m', $request->month )
                ->format( 'Y-m' );

            // 🔹 Lock employee row (finance safety)
            $employee = Employee::lockForUpdate()
                ->findOrFail( $request->employee_id );

            // 🔹 Prevent duplicate advance for same employee + month
            $exists = AdvanceSalary::where( 'employee_id', $employee->id )
                ->where( 'month', $month )
                ->lockForUpdate()
                ->exists();

            if ( $exists ) {
                DB::rollBack();
                return back()->with(
                    'error',
                    'Advance already taken for this month'
                );
            }

            // 🔹 Create advance record
            AdvanceSalary::create( [
                'employee_id' => $employee->id,
                'amount'      => (float) $request->amount,
                'month'       => $month,
                'taken_date'  => now(),
            ] );

            DB::commit();

            return back()->with(
                'success',
                'Advance salary added successfully'
            );

        } catch ( \Throwable $e ) {

            DB::rollBack();

            report( $e ); // log error

            return back()->with(
                'error',
                'Failed to create advance salary'
            );
        }
    }

}
