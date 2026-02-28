<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalaryPaymentController;
use App\Http\Controllers\SalaryPaymentReportController;
use Illuminate\Support\Facades\Route;

// Route::controller( App\Http\Controllers\DashboardController::class )->group( function () {
//     Route::get( '/', 'index' )->name( 'dashboard' );
// } );

// Route::get( '/employees', [EmployeeController::class, 'index'] )->name( 'employee.list' );
// Route::get( '/employees/create', [EmployeeController::class, 'create'] )->name( 'employee.create' );
// Route::post( '/employees/store', [EmployeeController::class, 'store'] )->name( 'employee.store' );

// Route::get( '/payroll/generate', [PayrollController::class, 'create'] )
//     ->name( 'payroll.create' );

// Route::post( '/payroll/generate', [PayrollController::class, 'generate'] )
//     ->name( 'payroll.generate' );

// Route::get( '/loans/create', [LoanController::class, 'create'] )->name( 'loan.create' );
// Route::post( '/loans/store', [LoanController::class, 'store'] )->name( 'loan.store' );

// Route::get( '/report', [ReportController::class, 'index'] )->name( 'report.index' );
// Route::get( '/report/pdf', [ReportController::class, 'pdf'] )->name( 'report.pdf' );

// Route::get( '/report/payslip/{employee}/{month}',
//     [ReportController::class, 'payslip'] )
//     ->name( 'report.payslip' );

// Route::post( '/salary/pay/{payroll}',
//     [SalaryPaymentController::class, 'pay']
// )->name( 'salary.pay' );

// Route::get( '/salary-payment-report',
//     [SalaryPaymentReportController::class, 'index']
// )->name( 'salary.payment.report' );

// Route::get( '/salary-payment-slip/{payment}',
//     [SalaryPaymentReportController::class, 'slip']
// )->name( 'salary.payment.slip' );

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
} );