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
        Schema::table('daily_panchangs', function (Blueprint $table) {
            $table->boolean('is_pakhi')->default(0)->nullable()->after('paksha');
            $table->string('today_event')->nullable()->after('is_pakhi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_panchangs', function (Blueprint $table) {
            $table->dropColumn(['is_pakhi', 'today_event']);
        });
    }
};
