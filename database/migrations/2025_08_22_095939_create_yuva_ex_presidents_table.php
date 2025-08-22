<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('yuva_ex_presidents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('karyakal')->nullable();
            $table->string('city')->nullable();
            $table->string('photo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yuva_ex_presidents');
    }
};
