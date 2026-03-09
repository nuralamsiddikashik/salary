<?php

namespace App\Http\Controllers;

use App\Models\AdvanceSalary;
use App\Models\Employee;
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller {

    // private function getEmployees( Request $request, string $month ) {
    //     $formattedMonth = Carbon::createFromFormat( 'Y-m', $month )->format( 'Y-m' );

    //     $employeeId = $request->integer( 'employee_id' );

    //     return Employee::query()
    //         ->when( $employeeId, fn( $q ) => $q->where( 'id', $employeeId ) )

    //         ->whereHas( 'payrolls', fn( $q ) =>
    //             $q->where( 'month', $formattedMonth )
    //         )

    //         ->with( [
    //             'payrolls' => fn( $q ) =>
    //             $q->where( 'month', $formattedMonth ),

    //             // 🔹 Advance salary for the same month
    //             'advances' => fn( $q ) =>
    //             $q->where( 'month', $formattedMonth ),
    //         ] )

    //         ->orderBy( 'name' )
    //         ->get();
    // }

    private function getEmployees( Request $request, string $month ) {
        $formattedMonth = Carbon::createFromFormat( 'Y-m', $month )->format( 'Y-m' );

        $employeeId = $request->integer( 'employee_id' );

        return Employee::query()
            ->when( $employeeId, fn( $q ) => $q->where( 'id', $employeeId ) )

            ->whereHas( 'payrolls', fn( $q ) =>
                $q->where( 'month', $formattedMonth )
            )

            ->with( [
                'payrolls' => fn( $q ) =>
                $q->where( 'month', $formattedMonth ),

                'advances' => fn( $q ) =>
                $q->where( 'month', $formattedMonth ),
            ] )

            ->orderBy( 'name' )

            ->paginate( 50 ); // 🔹 50 records per page
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
    // public function pdf( Request $request ) {
    //     $month = $request->month ?? now()->format( 'Y-m' );

    //     $employees = $this->getEmployees( $request, $month );
    //     $chunks    = $employees->chunk( 15 );

    //     $pdf = Pdf::loadView( 'report.pdf', compact( 'employees', 'chunks', 'month' ) )
    //         ->setPaper( 'a4', 'landscape' );

    //     return $pdf->download( 'payroll-report-' . $month . '.pdf' );
    // }

    // public function pdf( Request $request ) {
    //     $month = $request->month ?? now()->format( 'Y-m' );

    //     $employees = $this->getEmployees( $request, $month );

    //     $pdf = Pdf::loadView( 'report.pdf', compact( 'employees', 'month' ) )
    //         ->setPaper( 'a4', 'landscape' );

    //     return $pdf->stream( 'payroll-report-' . $month . '.pdf' );
    // }

    public function pdf( Request $request ) {
        $month = $request->month ?? now()->format( 'Y-m' );

        $employees = $this->getEmployees( $request, $month );

        $pdf = Pdf::loadView( 'report.pdf', compact( 'employees', 'month' ) )
            ->setPaper( 'a4', 'landscape' );

        // যদি download request আসে
        if ( $request->type === 'download' ) {
            return $pdf->download( 'payroll-report-' . $month . '.pdf' );
        }

        // default preview (browser open)
        return $pdf->stream( 'payroll-report-' . $month . '.pdf' );
    }

    /**
     * Individual Payslip
     */
    public function payslip( $employeeId, $month ) {
        $month = Carbon::parse( $month )->format( 'Y-m' );

        $payroll = Payroll::with( ['employee'] )
            ->where( 'employee_id', $employeeId )
            ->where( 'month', $month )
            ->firstOrFail();

        // 🔹 Advance Salary for this month
        $advanceAmount = AdvanceSalary::where( 'employee_id', $employeeId )
            ->where( 'month', $month )
            ->sum( 'amount' );

        return view( 'report.payslip', compact( 'payroll', 'advanceAmount' ) );
    }
}