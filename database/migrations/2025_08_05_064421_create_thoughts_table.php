<?php

// database/migrations/xxxx_xx_xx_create_thoughts_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('thoughts', function (Blueprint $table) {
            $table->id();
            $table->text('thought');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('thoughts');
    }
};

