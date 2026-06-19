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
        Schema::table('spf_events', function (Blueprint $table) {
            $table->string('registration_link')->nullable()->after('event_reg_close');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spf_events', function (Blueprint $table) {
            $table->dropColumn('registration_link');
        });
    }
};
