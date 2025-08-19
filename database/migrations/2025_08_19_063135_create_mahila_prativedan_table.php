<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahila_prativedan', function (Blueprint $table) {
            $table->id();
            $table->string('name');  
            $table->string('google_drive_link');  
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahila_prativedan');
    }
};
