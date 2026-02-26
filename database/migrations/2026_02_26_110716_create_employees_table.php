<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'employees', function ( Blueprint $table ) {
            $table->id();
            $table->string( 'name' );
            $table->date( 'join_date' );
            $table->string( 'designation' );

            $table->decimal( 'total_salary', 12, 2 );
            $table->decimal( 'basic_salary', 12, 2 );
            $table->decimal( 'house_rent', 12, 2 );
            $table->decimal( 'medical', 12, 2 );
            $table->decimal( 'conveyance', 12, 2 );

            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'employees' );
    }
};
