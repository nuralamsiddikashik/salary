<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalaryPaymentController;
use App\Http\Controllers\SalaryPaymentReportController;
use Illuminate\Support\Facades\Route;

Route::middleware( 'guest' )->group( function () {

    Route::get( '/login', [AuthController::class, 'showLogin'] )
        ->name( 'login' );

    Route::post( '/login', [AuthController::class, 'login'] )
        ->name( 'login.submit' );

} );

Route::middleware( 'auth' )->group( function () {

    Route::controller( DashboardController::class )->group( function () {
        Route::get( '/', 'index' )->name( 'dashboard' );
    } );

    Route::controller( EmployeeController::class )->prefix( 'employees' )->group( function () {
        Route::get( '/', 'index' )->name( 'employee.list' );
        Route::get( '/create', 'create' )->name( 'employee.create' );
        Route::post( '/store', 'store' )->name( 'employee.store' );
    } );

    Route::controller( PayrollController::class )->prefix( 'payroll' )->group( function () {
        Route::get( '/generate', 'create' )->name( 'payroll.create' );
        Route::post( '/generate', 'generate' )->name( 'payroll.generate' );
        Route::delete( '/payroll/{id}', 'destroy' )->name( 'payroll.destroy' );
    } );

    Route::controller( LoanController::class )->prefix( 'loans' )->group( function () {
        Route::get( '/create', 'create' )->name( 'loan.create' );
        Route::post( '/store', 'store' )->name( 'loan.store' );
    } );

    Route::controller( ReportController::class )->prefix( 'report' )->group( function () {
        Route::get( '/', 'index' )->name( 'report.index' );
        Route::get( '/pdf', 'pdf' )->name( 'report.pdf' );
        Route::get( '/payslip/{employee}/{month}', 'payslip' )->name( 'report.payslip' );
    } );

    Route::controller( SalaryPaymentController::class )->group( function () {
        Route::post( '/salary/pay/{payroll}', 'pay' )->name( 'salary.pay' );
    } );

    Route::controller( SalaryPaymentReportController::class )->group( function () {
        Route::get( '/salary-payment-report', 'index' )->name( 'salary.payment.report' );
        Route::get( '/salary-payment-slip/{payment}', 'slip' )->name( 'salary.payment.slip' );
        Route::get( '/salary-payment-report/pdf', 'exportPdf' )
            ->name( 'salary.payment.report.pdf' );
    } );

    Route::post( '/logout', [AuthController::class, 'logout'] )
        ->name( 'logout' );

} );
