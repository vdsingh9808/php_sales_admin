<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->json('menu_name')->nullable()->after('is_default');
        });
    }


    public function down(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->dropColumn('menu_name');
        });
    }
};
