<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
   
{
    Schema::table('themes', function (Blueprint $table) {
        if (!Schema::hasColumn('themes', 'text_heading_secondary')) {
            $table->string('text_heading_secondary')->after('text_heading');
        }
    });

    // Rename only if 'navbar' exists
    if (Schema::hasColumn('themes', 'navbar')) {
        DB::statement("ALTER TABLE `themes` CHANGE COLUMN `navbar` `header` VARCHAR(255)");
    }
}


    public function down(): void
   
{
    Schema::table('themes', function (Blueprint $table) {
        if (Schema::hasColumn('themes', 'text_heading_secondary')) {
            $table->dropColumn('text_heading_secondary');
        }
    });

    // Rename back only if 'header' exists
    if (Schema::hasColumn('themes', 'header')) {
        DB::statement("ALTER TABLE `themes` CHANGE COLUMN `header` `navbar` VARCHAR(255)");
    }
}

};
