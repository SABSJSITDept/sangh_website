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
        Schema::table('sanyojan_mandal_antrastriya_sadasyata', function (Blueprint $table) {
            $table->string('session')->default('2025-27')->after('photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sanyojan_mandal_antrastriya_sadasyata', function (Blueprint $table) {
            $table->dropColumn('session');
        });
    }
};
