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
        Schema::create('daily_panchangs', function (Blueprint $table) {
            $table->id();

            // API response se: "date": "2026-05-15"
            $table->date('date')->unique()->comment('Panchang date from API response');

            // lunar_month se: "name": "Vaisakha", "vikram_samvat": 2083
            $table->string('lunar_month_name')->nullable()->comment('Lunar month name e.g. Vaisakha');
            $table->unsignedSmallInteger('vikram_samvat')->nullable()->comment('Vikram Samvat year');

            // tithi se: "number", "name", "paksha"
            $table->unsignedTinyInteger('tithi_number')->nullable()->comment('Tithi number 1-30');
            $table->string('tithi')->nullable()->comment('Tithi name e.g. Trayodashi');
            $table->string('paksha')->nullable()->comment('Shukla or Krishna');

            // request_time_panchang.tithi se: "name" → tithi_two field
            $table->string('tithi_two')->nullable()->comment('Tithi at request time from request_time_panchang');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_panchangs');
    }
};
