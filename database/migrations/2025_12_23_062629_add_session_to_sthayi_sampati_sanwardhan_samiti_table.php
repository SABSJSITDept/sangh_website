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
        Schema::table('sthayi_sampati_sanwardhan_samiti', function (Blueprint $table) {
            $table->string('session')->default('2025-27')->after('photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sthayi_sampati_sanwardhan_samiti', function (Blueprint $table) {
            $table->dropColumn('session');
        });
    }
};
