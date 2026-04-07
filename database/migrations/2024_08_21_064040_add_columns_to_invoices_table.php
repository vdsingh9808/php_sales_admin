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
            $table->string('invoice_name')->nullable() ;
            $table->string('customer_type')->nullable() ;
            $table->text('customer_type_value')->nullable() ;
            $table->text('legal_entity')->nullable() ;
            $table->text('unit_rental')->nullable() ;
            $table->text('profile_code')->nullable() ;
            $table->text('transaction_account')->nullable() ;
            $table->text('property_unit')->nullable() ;
            $table->text('property')->nullable() ;
            $table->text('property_transaction_category')->nullable() ;
            $table->date('receipt_date')->nullable() ;
            $table->string('receipt_id')->nullable() ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            
        });
    }
};
