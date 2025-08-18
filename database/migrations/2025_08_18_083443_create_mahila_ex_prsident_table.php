<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mahila_ex_prsident', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('karyakal');
            $table->string('place');
            $table->string('photo'); // image path
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahila_ex_prsident');
    }
};
