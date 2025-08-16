<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pakhi_ka_panna', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->string('pdf'); // store pdf path
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pakhi_ka_panna');
    }
};
