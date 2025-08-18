<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('mahila_pst', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('post', ['अध्यक्ष', 'महामंत्री', 'कोषाध्यक्ष', 'सह कोषाध्यक्ष'])->unique();
            $table->string('photo');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('mahila_pst');
    }
};
