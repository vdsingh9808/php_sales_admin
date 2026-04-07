<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('text_heading');
            $table->string('text_title');
            $table->string('text_body');
            $table->string('button_primary');
            $table->string('button_secondary');
            $table->string('dashboard');
            $table->string('menu');
            $table->string('navbar');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
