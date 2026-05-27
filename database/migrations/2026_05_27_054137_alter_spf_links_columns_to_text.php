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
        Schema::table('spf_links', function (Blueprint $table) {
            $table->text('mobile_number')->nullable()->change();
            $table->text('whatsapp_number')->nullable()->change();
            $table->text('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spf_links', function (Blueprint $table) {
            $table->string('mobile_number')->nullable()->change();
            $table->string('whatsapp_number')->nullable()->change();
            $table->string('email')->nullable()->change();
        });
    }
};
