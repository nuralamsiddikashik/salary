<?php

use App\Http\Controllers\AdvanceSalaryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalaryPaymentController;
use App\Http\Controllers\SalaryPaymentReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::middleware( 'guest' )->group( function () {

//     Route::get( '/login', [AuthController::class, 'showLogin'] )
//         ->name( 'login' );

//     Route::post( '/login', [AuthController::class, 'login'] )
//         ->name( 'login.submit' );

// } );

// Route::middleware( 'auth' )->group( function () {

//     Route::controller( DashboardController::class )->group( function () {
//         Route::get( '/', 'index' )->name( 'dashboard' );
//     } );

//     Route::controller( EmployeeController::class )->prefix( 'employees' )->group( function () {
//         Route::get( '/', 'index' )->name( 'employee.list' );
//         Route::get( '/create', 'create' )->name( 'employee.create' );
//         Route::post( '/store', 'store' )->name( 'employee.store' );
//     } );

//     Route::controller( PayrollController::class )->prefix( 'payroll' )->group( function () {
//         Route::get( '/generate', 'create' )->name( 'payroll.create' );
//         Route::post( '/generate', 'generate' )->name( 'payroll.generate' );
//         Route::delete( '/payroll/{id}', 'destroy' )->name( 'payroll.destroy' );
//     } );

//     Route::controller( LoanController::class )->prefix( 'loans' )->group( function () {
//         Route::get( '/create', 'create' )->name( 'loan.create' );
//         Route::post( '/store', 'store' )->name( 'loan.store' );
//     } );

//     Route::controller( ReportController::class )->prefix( 'report' )->group( function () {
//         Route::get( '/', 'index' )->name( 'report.index' );
//         Route::get( '/pdf', 'pdf' )->name( 'report.pdf' );
//         Route::get( '/payslip/{employee}/{month}', 'payslip' )->name( 'report.payslip' );
//     } );

//     Route::controller( SalaryPaymentController::class )->group( function () {
//         Route::post( '/salary/pay/{payroll}', 'pay' )->name( 'salary.pay' );
//         Route::post( '/salary/bulk-pay', 'bulkPay' )
//             ->name( 'salary.bulk.pay' );
//     } );

//     Route::controller( SalaryPaymentReportController::class )->group( function () {
//         Route::get( '/salary-payment-report', 'index' )->name( 'salary.payment.report' );
//         Route::get( '/salary-payment-slip/{payment}', 'slip' )->name( 'salary.payment.slip' );
//         Route::get( '/salary-payment-report/pdf', 'exportPdf' )
//             ->name( 'salary.payment.report.pdf' );
//     } );

//     Route::post( '/logout', [AuthController::class, 'logout'] )
//         ->name( 'logout' );

//     Route::controller( AdvanceSalaryController::class )->prefix( 'advance' )->group( function () {

//         Route::get( '/create', 'create' )->name( 'advance.create' );

//         Route::post( '/store', 'store' )->name( 'advance.store' );

//         Route::get( '/list', 'index' )->name( 'advance.list' );
//         Route::get( '/advance-report', 'index' )->name( 'advance.index' );

//     } );

// } );

Route::middleware( 'guest' )->group( function () {

    Route::get( '/login', [AuthController::class, 'showLogin'] )->name( 'login' );
    Route::post( '/login', [AuthController::class, 'login'] )->name( 'login.submit' );

} );

Route::middleware( 'auth' )->group( function () {

    // ✅ Dashboard (সবাই দেখতে পারবে login করলে)
    Route::get( '/', [DashboardController::class, 'index'] )->name( 'dashboard' );

    /*
    |--------------------------------------------------------------------------
    | Employee
    |--------------------------------------------------------------------------
     */
    Route::prefix( 'employees' )->group( function () {

        Route::get( '/', [EmployeeController::class, 'index'] )
            ->name( 'employee.list' )
            ->middleware( 'permission:employee.view' );

        Route::get( '/create', [EmployeeController::class, 'create'] )
            ->name( 'employee.create' )
            ->middleware( 'permission:employee.create' );

        Route::post( '/store', [EmployeeController::class, 'store'] )
            ->name( 'employee.store' )
            ->middleware( 'permission:employee.create' );

    } );

    /*
    |--------------------------------------------------------------------------
    | Payroll
    |--------------------------------------------------------------------------
     */
    Route::prefix( 'payroll' )->group( function () {

        Route::get( '/generate', [PayrollController::class, 'create'] )
            ->name( 'payroll.create' )
            ->middleware( 'permission:payroll.create' );

        Route::post( '/generate', [PayrollController::class, 'generate'] )
            ->name( 'payroll.generate' )
            ->middleware( 'permission:payroll.create' );

        Route::delete( '/payroll/{id}', [PayrollController::class, 'destroy'] )
            ->name( 'payroll.destroy' )
            ->middleware( 'permission:payroll.delete' );

    } );

    /*
    |--------------------------------------------------------------------------
    | Loan
    |--------------------------------------------------------------------------
     */
    Route::prefix( 'loans' )->group( function () {

        Route::get( '/create', [LoanController::class, 'create'] )
            ->name( 'loan.create' )
            ->middleware( 'permission:loan.create' );

        Route::post( '/store', [LoanController::class, 'store'] )
            ->name( 'loan.store' )
            ->middleware( 'permission:loan.create' );

    } );

    /*
    |--------------------------------------------------------------------------
    | Report
    |--------------------------------------------------------------------------
     */
    Route::prefix( 'report' )->group( function () {

        Route::get( '/', [ReportController::class, 'index'] )
            ->name( 'report.index' )
            ->middleware( 'permission:report.view' );

        Route::get( '/pdf', [ReportController::class, 'pdf'] )
            ->name( 'report.pdf' )
            ->middleware( 'permission:report.view' );

        Route::get( '/payslip/{employee}/{month}', [ReportController::class, 'payslip'] )
            ->name( 'report.payslip' )
            ->middleware( 'permission:report.view' );

    } );

    /*
    |--------------------------------------------------------------------------
    | Salary Payment
    |--------------------------------------------------------------------------
     */
    Route::post( '/salary/pay/{payroll}', [SalaryPaymentController::class, 'pay'] )
        ->name( 'salary.pay' )
        ->middleware( 'permission:salary.pay' );

    Route::post( '/salary/bulk-pay', [SalaryPaymentController::class, 'bulkPay'] )
        ->name( 'salary.bulk.pay' )
        ->middleware( 'permission:salary.pay' );

    /*
    |--------------------------------------------------------------------------
    | Salary Report
    |--------------------------------------------------------------------------
     */
    Route::get( '/salary-payment-report', [SalaryPaymentReportController::class, 'index'] )
        ->name( 'salary.payment.report' )
        ->middleware( 'permission:report.view' );

    Route::get( '/salary-payment-slip/{payment}', [SalaryPaymentReportController::class, 'slip'] )
        ->name( 'salary.payment.slip' )
        ->middleware( 'permission:report.view' );

    Route::get( '/salary-payment-report/pdf', [SalaryPaymentReportController::class, 'exportPdf'] )
        ->name( 'salary.payment.report.pdf' )
        ->middleware( 'permission:report.view' );

    /*
    |--------------------------------------------------------------------------
    | Advance Salary
    |--------------------------------------------------------------------------
     */
    Route::prefix( 'advance' )->group( function () {

        Route::get( '/create', [AdvanceSalaryController::class, 'create'] )
            ->name( 'advance.create' )
            ->middleware( 'permission:advance.create' );

        Route::post( '/store', [AdvanceSalaryController::class, 'store'] )
            ->name( 'advance.store' )
            ->middleware( 'permission:advance.create' );

        Route::get( '/list', [AdvanceSalaryController::class, 'index'] )
            ->name( 'advance.list' )
            ->middleware( 'permission:advance.create' );

    } );

    /*
    |--------------------------------------------------------------------------
    | User Management (Admin only)
    |--------------------------------------------------------------------------
     */
    // Route::middleware( 'permission:user.manage' )->group( function () {

    //     Route::get( '/users', [UserController::class, 'index'] )->name( 'users.index' );
    //     Route::post( '/users/{id}', [UserController::class, 'update'] )->name( 'user.update' );

    // } );

    Route::middleware( ['auth', 'permission:user.manage'] )->group( function () {

        // 🔹 User list
        Route::get( '/users', [UserController::class, 'index'] )
            ->name( 'users.index' );

        // 🔹 Create user form
        Route::get( '/users/create', [UserController::class, 'create'] )
            ->name( 'users.create' );

        // 🔹 Store new user
        Route::post( '/users/store', [UserController::class, 'store'] )
            ->name( 'users.store' );

        // 🔹 Update user (role + permission + suspend)
        Route::post( '/users/{id}', [UserController::class, 'update'] )
            ->name( 'user.update' );

    } );

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
     */
    Route::post( '/logout', [AuthController::class, 'logout'] )->name( 'logout' );

} );