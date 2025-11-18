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
        Schema::create('jsp_result', function (Blueprint $table) {
            $table->id();
            $table->string('Student_Name')->nullable();
            $table->string('Guardian_Name')->nullable();
            $table->string('Mobile')->nullable();
            $table->string('City')->nullable();
            $table->string('State')->nullable();
            $table->string('Class')->nullable();
            $table->integer('Marks')->nullable();
            $table->integer('Rank')->nullable();
            $table->text('Remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jsp_result');
    }
};
