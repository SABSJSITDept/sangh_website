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
        Schema::table('vp_sec', function (Blueprint $table) {
            $table->string('aanchal')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vp_sec', function (Blueprint $table) {
            $table->string('aanchal')->nullable(false)->change();
        });
    }
};
