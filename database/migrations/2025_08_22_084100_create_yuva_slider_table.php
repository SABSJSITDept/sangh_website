<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('yuva_slider', function (Blueprint $table) {
            $table->id();
            $table->string('image'); // only image file path
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yuva_slider');
    }
};
