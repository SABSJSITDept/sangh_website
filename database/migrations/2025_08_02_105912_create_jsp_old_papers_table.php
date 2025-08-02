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
       Schema::create('jsp_old_papers', function (Blueprint $table) {
    $table->id();
    $table->unsignedTinyInteger('class');
    $table->year('year');
    $table->string('pdf');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jsp_old_papers');
    }
};
