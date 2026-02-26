<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller {
    /**
     * Common query builder for both index and pdf
     */
    private function getEmployees( Request $request, string $month ) {
        return Employee::with( [
            'payrolls' => function ( $q ) use ( $month ) {
                $q->where( 'month', $month )->latest();
            },
            'loans'    => function ( $q ) {
                $q->latest();
            },
        ] )
            ->when( $request->employee_id, function ( $q ) use ( $request ) {
                $q->where( 'id', $request->employee_id );
            } )
            ->when( $month, function ( $q ) use ( $month ) {
                $q->whereHas( 'payrolls', function ( $q ) use ( $month ) {
                    $q->where( 'month', $month );
                } );
            } )
            ->when( $request->year, function ( $q ) use ( $request ) {
                $q->whereHas( 'payrolls', function ( $q ) use ( $request ) {
                    $q->whereYear( 'created_at', $request->year );
                } );
            } )
            ->orderBy( 'name' )
            ->get();
    }

    /**
     * Main report page
     */
    public function index( Request $request ) {
        $month       = $request->month ?? now()->format( 'Y-m' );
        $year        = $request->year;
        $employee_id = $request->employee_id;
        $employees   = $this->getEmployees( $request, $month );

        return view( 'report.index', compact(
            'employees', 'month', 'year', 'employee_id'
        ) );
    }

    /**
     * Generate & download PDF
     */
    public function pdf( Request $request ) {
        $month     = $request->month ?? now()->format( 'Y-m' );
        $year      = $request->year;
        $employees = $this->getEmployees( $request, $month );
        $chunks    = $employees->chunk( 15 ); // 10 rows per page

        $pdf = Pdf::loadView( 'report.pdf', compact( 'employees', 'chunks', 'month', 'year' ) )
            ->setPaper( 'a4', 'landscape' )
            ->setOptions( [
                'defaultFont'          => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled'         => true,
                'dpi'                  => 150,
            ] );

        return $pdf->download( 'payroll-report-' . $month . '.pdf' );
    }

    public function payslip( $employeeId, $month ) {
        $payroll = Payroll::with( 'employee' )
            ->where( 'employee_id', $employeeId )
            ->where( 'month', $month )
            ->firstOrFail();

        return view( 'report.payslip', compact( 'payroll' ) );
    }
}