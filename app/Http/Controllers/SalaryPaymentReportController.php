<?php
namespace App\Http\Controllers;

use App\Models\SalaryPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalaryPaymentReportController extends Controller {

    public function index( Request $request ) {
        $month = $request->month ?? now()->format( 'Y-m' );
        $month = Carbon::parse( $month )->format( 'Y-m' );

        $paymentType = $request->payment_type;

        $payments = SalaryPayment::with( ['employee', 'payroll'] )
            ->whereRelation( 'payroll', 'month', $month )
            ->when( $paymentType, function ( $q ) use ( $paymentType ) {
                $q->where( 'payment_type', $paymentType );
            } )
            ->latest( 'payment_date' )
            ->get();

        return view( 'salary-report.index', compact(
            'payments',
            'month',
            'paymentType'
        ) );
    }

    public function slip( SalaryPayment $payment ) {
        $payment->load( 'employee', 'payroll' );

        return view( 'salary-report.salary-payslip', compact( 'payment' ) );
    }

    public function exportPdf( Request $request ) {
        $month       = $request->month ?? now()->format( 'Y-m' );
        $month       = Carbon::parse( $month )->format( 'Y-m' );
        $paymentType = $request->payment_type;

        $payments = SalaryPayment::with( ['employee', 'payroll'] )
            ->whereHas( 'payroll', function ( $q ) use ( $month ) {
                $q->where( 'month', $month );
            } )
            ->when( $paymentType, function ( $q ) use ( $paymentType ) {
                $q->where( 'payment_type', $paymentType );
            } )
            ->orderBy( 'payment_date' )
            ->get();

        $pdf = Pdf::loadView( 'salary-report.pdf', compact( 'payments', 'month', 'paymentType' ) )
            ->setPaper( 'a4', 'portrait' );

        return $pdf->download( 'salary-payment-report-' . $month . '.pdf' );
    }
}