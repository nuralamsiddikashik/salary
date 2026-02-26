<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'payrolls', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'employee_id' )->constrained()->onDelete( 'cascade' );

            $table->string( 'month' );
            $table->integer( 'absent_days' )->default( 0 );
            $table->decimal( 'absent_amount', 12, 2 )->default( 0 );
            $table->decimal( 'loan_deduction', 12, 2 )->default( 0 );
            $table->decimal( 'net_payable', 12, 2 );

            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'payrolls' );
    }
};
