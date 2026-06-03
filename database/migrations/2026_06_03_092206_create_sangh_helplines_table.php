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
        Schema::create('sangh_helplines', function (Blueprint $table) {
            $table->id();
            $table->string('dept_name');
            $table->json('mobile_number')->nullable();
            $table->json('email')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->boolean('is_only_whatsapp')->default(false);
            $table->integer('sequence')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sangh_helplines');
    }
};
