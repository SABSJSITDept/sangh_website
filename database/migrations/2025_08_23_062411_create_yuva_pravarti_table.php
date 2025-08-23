<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('yuva_pravarti', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->text('content');
            $table->string('photo')->nullable(); // optional image
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yuva_pravarti');
    }
};
