<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 // database/migrations/xxxx_xx_xx_create_jsp_gujrati_books_table.php
public function up()
{
    Schema::create('jsp_gujrati_books', function (Blueprint $table) {
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
        Schema::dropIfExists('jsp_gujrati_books');
    }
};
