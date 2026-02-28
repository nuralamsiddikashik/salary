<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller {

    // private function getEmployees( Request $request, string $month ) {
    //     $month = Carbon::parse( $month )->format( 'Y-m' );

    //     return Employee::with( [
    //         'payrolls' => function ( $q ) use ( $month ) {
    //             $q->where( 'month', $month );
    //         },
    //     ] )
    //         ->when( $request->employee_id, function ( $q ) use ( $request ) {
    //             $q->where( 'id', $request->employee_id );
    //         } )
    //         ->whereHas( 'payrolls', function ( $q ) use ( $month ) {
    //             $q->where( 'month', $month );
    //         } )
    //         ->orderBy( 'name' )
    //         ->get();
    // }

    private function getEmployees( Request $request, string $month ) {
        // ✅ Strict month validation (format: YYYY-MM)
        try {
            $formattedMonth = Carbon::createFromFormat( 'Y-m', $month )
                ->format( 'Y-m' );
        } catch ( \Exception $e ) {
            throw ValidationException::withMessages( [
                'month' => 'Invalid month format. Expected format: YYYY-MM',
            ] );
        }

        // ✅ Safe integer casting (prevents invalid input)
        $employeeId = $request->integer( 'employee_id' );

        return Employee::query()
            ->when( $employeeId, function ( $query ) use ( $employeeId ) {
                $query->where( 'id', $employeeId );
            } )
            ->whereHas( 'payrolls', function ( $query ) use ( $formattedMonth ) {
                $query->where( 'month', $formattedMonth );
            } )
            ->with( [
                'payrolls' => function ( $query ) use ( $formattedMonth ) {
                    $query->where( 'month', $formattedMonth );
                },
            ] )
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