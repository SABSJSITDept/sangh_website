<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shramnopasak', function (Blueprint $table) {
    $table->id();
    $table->year('year');
    $table->string('month');
    $table->string('cover_photo')->nullable();
    $table->string('pdf')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shramnopasak');
    }
};
