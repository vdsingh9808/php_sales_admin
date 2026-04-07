<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->dropColumn('text_heading');
            $table->dropColumn('text_heading_secondary');
            $table->dropColumn('text_title');
            $table->dropColumn('text_body');
            $table->dropColumn('dashboard');
            $table->dropColumn('menu');
            $table->dropColumn('header');
        });
    }


    public function down(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->string('text_heading');
            $table->string('text_heading_secondary');
            $table->string('text_title');
            $table->string('text_body');
            $table->string('dashboard');
            $table->string('menu');
            $table->string('header');
        });
    }
};
