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
    Schema::create('mahila_pravarti_snyojaka', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('city');
        $table->string('post'); // Dropdown se select hoga
        $table->string('mobile', 15);
        $table->string('photo'); // storage path
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('mahila_pravarti_snyojaka');
}

};
