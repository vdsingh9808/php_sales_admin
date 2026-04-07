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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invoice_line_id')->nullable() ;
            $table->string('payment_schedule_value')->nullable();
            $table->string('receipt_distribution_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('invoice_line_id');
            $table->dropColumn('payment_schedule_value');
            $table->dropColumn('receipt_distribution_id');
        });
    }
};
