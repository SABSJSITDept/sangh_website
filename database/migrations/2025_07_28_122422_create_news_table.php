<?php

// database/migrations/xxxx_xx_xx_create_news_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->time('time');
            $table->string('location');
            $table->text('description');
            $table->string('photo');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('news');
    }
};
