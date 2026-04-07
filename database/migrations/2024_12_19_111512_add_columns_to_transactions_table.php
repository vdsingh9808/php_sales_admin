<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('receipt_processing_status')->nullable() ;
            $table->boolean('payment_status_updated')->default(0) ;
            $table->dropColumn('api_progress_level') ;
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->string('receipt_distribution_status')->nullable() ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('receipt_processing_status') ;
            $table->string('api_progress_level')->nullable() ;
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('receipt_distribution_status') ;
        });
    }
};
