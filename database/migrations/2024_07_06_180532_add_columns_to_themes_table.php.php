<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->string('general_heading')->after('name');
            $table->string('general_body')->after('general_heading');
            $table->string('general_icons')->after('general_body');
            $table->string('general_background')->after('general_icons');
            $table->string('table_heading')->after('general_background');
            $table->string('table_title')->after('table_heading');
            $table->string('table_body')->after('table_title');
            $table->string('header_background')->after('table_body');
            $table->string('header_text')->after('header_background');
            $table->string('navbar_background')->after('header_text');
            $table->string('navbar_text')->after('navbar_background');
        });
    }


    public function down(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->dropColumn('general_heading');
            $table->dropColumn('general_body');
            $table->dropColumn('general_icons');
            $table->dropColumn('general_background');
            $table->dropColumn('table_heading');
            $table->dropColumn('table_title');
            $table->dropColumn('table_body');
            $table->dropColumn('header_background');
            $table->dropColumn('header_text');
            $table->dropColumn('navbar_background');
            $table->dropColumn('navbar_text');
        });
    }
};
