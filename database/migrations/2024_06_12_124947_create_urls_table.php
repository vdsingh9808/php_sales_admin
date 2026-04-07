<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('urls', function (Blueprint $table) {
            $table->id();
            $table->string('authentication_url');
            $table->string('server_url');
            $table->string('payment_base_url');
            $table->string('api_key');
            $table->string('reference_key');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('urls');
    }
};
