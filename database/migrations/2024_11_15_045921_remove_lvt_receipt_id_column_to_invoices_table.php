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
            $table->dropColumn('receipt_id');
            $table->dropColumn('lvt_id');
            $table->dropColumn('receipt_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('receipt_id')->nullable();
            $table->string('lvt_id')->nullable();
            $table->date('receipt_date')->nullable() ;
        });
    }
};
