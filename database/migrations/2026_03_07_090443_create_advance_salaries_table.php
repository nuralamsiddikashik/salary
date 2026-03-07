<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'advance_salaries', function ( Blueprint $table ) {

            $table->id();

            $table->foreignId( 'employee_id' )
                ->constrained()
                ->cascadeOnDelete();

            $table->string( 'month' ); // 2026-03

            $table->decimal( 'amount', 12, 2 );

            $table->date( 'taken_date' );

            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'advance_salaries' );
    }
};
