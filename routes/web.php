<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::controller( App\Http\Controllers\DashboardController::class )->group( function () {
    Route::get( '/', 'index' )->name( 'dashboard' );
} );

Route::get( '/employees', [EmployeeController::class, 'index'] )->name( 'employee.list' );
Route::get( '/employees/create', [EmployeeController::class, 'create'] )->name( 'employee.create' );
Route::post( '/employees/store', [EmployeeController::class, 'store'] )->name( 'employee.store' );

Route::get( '/payroll/generate', [PayrollController::class, 'create'] )
    ->name( 'payroll.create' );

Route::post( '/payroll/generate', [PayrollController::class, 'generate'] )
    ->name( 'payroll.generate' );

Route::get( '/loans/create', [LoanController::class, 'create'] )->name( 'loan.create' );
Route::post( '/loans/store', [LoanController::class, 'store'] )->name( 'loan.store' );

Route::get( '/report', [ReportController::class, 'index'] )->name( 'report.index' );
Route::get( '/report/pdf', [ReportController::class, 'pdf'] )->name( 'report.pdf' );

Route::get( '/report/payslip/{employee}/{month}',
    [ReportController::class, 'payslip'] )
    ->name( 'report.payslip' );