<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('spf_events', function (Blueprint $table) {
            $table->date('event_reg_start')->nullable()->after('date');
            $table->date('event_reg_close')->nullable()->after('event_reg_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spf_events', function (Blueprint $table) {
            $table->dropColumn(['event_reg_start', 'event_reg_close']);
        });
    }
};
