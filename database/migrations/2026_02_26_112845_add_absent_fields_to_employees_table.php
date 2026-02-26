<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table( 'employees', function ( Blueprint $table ) {
            $table->integer( 'absent_days' )->default( 0 );
            $table->decimal( 'absent_amount', 12, 2 )->default( 0 );
        } );
    }

    public function down(): void {
        Schema::table( 'employees', function ( Blueprint $table ) {
            $table->dropColumn( ['absent_days', 'absent_amount'] );
        } );
    }
};
