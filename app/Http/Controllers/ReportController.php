<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller {
    /**
     * Common query builder
     */
    private function getEmployees( Request $request, string $month ) {
        $month = Carbon::parse( $month )->format( 'Y-m' );

        return Employee::with( [
            'payrolls' => function ( $q ) use ( $month ) {
                $q->where( 'month', $month );
            },
        ] )
            ->when( $request->employee_id, function ( $q ) use ( $request ) {
                $q->where( 'id', $request->employee_id );
            } )
            ->whereHas( 'payrolls', function ( $q ) use ( $month ) {
                $q->where( 'month', $month );
            } )
            ->orderBy( 'name' )
            ->get();
    }

    /**
     * Main report page
     */
    public function index( Request $request ) {
        $month = $request->month ?? now()->format( 'Y-m' );

        $employees = $this->getEmployees( $request, $month );

        return view( 'report.index', compact( 'employees', 'month' ) );
    }

    /**
     * Generate PDF
     */
    public function pdf( Request $request ) {
        $month = $request->month ?? now()->format( 'Y-m' );

        $employees = $this->getEmployees( $request, $month );
        $chunks    = $employees->chunk( 15 );

        $pdf = Pdf::loadView( 'report.pdf', compact( 'employees', 'chunks', 'month' ) )
            ->setPaper( 'a4', 'landscape' );

        return $pdf->download( 'payroll-report-' . $month . '.pdf' );
    }

    /**
     * Individual Payslip
     */
    public function payslip( $employeeId, $month ) {
        $month = Carbon::parse( $month )->format( 'Y-m' );

        $payroll = Payroll::with( 'employee' )
            ->where( 'employee_id', $employeeId )
            ->where( 'month', $month )
            ->firstOrFail();

        return view( 'report.payslip', compact( 'payroll' ) );
    }
}