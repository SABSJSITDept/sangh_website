<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahila_aavedan_patra', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of form
            $table->enum('type', ['pdf', 'google_form']); // pdf or google form
            $table->string('pdf')->nullable(); // pdf path
            $table->string('google_form_link')->nullable(); // google form link
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahila_aavedan_patra');
    }
};
