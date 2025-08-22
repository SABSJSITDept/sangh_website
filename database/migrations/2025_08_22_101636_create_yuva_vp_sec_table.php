<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('yuva_vp_sec', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('post', ['उपाध्यक्ष', 'मंत्री']);
            $table->string('city')->nullable();
            $table->string('aanchal');
            $table->string('mobile')->nullable();
            $table->string('photo'); // store image path
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yuva_vp_sec');
    }
};
