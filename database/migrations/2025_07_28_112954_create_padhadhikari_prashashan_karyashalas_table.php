<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_xx_xx_create_padhadhikari_prashashan_karyashalas_table.php
public function up()
{
    Schema::create('padhadhikari_prashashan_karyashalas', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('pdf');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('padhadhikari_prashashan_karyashalas');
    }
};
