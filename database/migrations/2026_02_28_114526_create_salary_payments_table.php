<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'salary_payments', function ( Blueprint $table ) {
            $table->id();

            $table->foreignId( 'employee_id' )
                ->constrained()
                ->onDelete( 'cascade' );

            $table->foreignId( 'payroll_id' )
                ->constrained()
                ->onDelete( 'cascade' );

            $table->decimal( 'paid_amount', 12, 2 );

            $table->enum( 'payment_type', [
                'first_half',
                'final_half',
                'full',
            ] );

            $table->date( 'payment_date' );

            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'salary_payments' );
    }
};
