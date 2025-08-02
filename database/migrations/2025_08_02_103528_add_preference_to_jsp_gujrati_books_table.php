<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_add_preference_to_jsp_gujrati_books_table.php
public function up()
{
    Schema::table('jsp_gujrati_books', function (Blueprint $table) {
        $table->integer('preference')->unique()->after('name');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jsp_gujrati_books', function (Blueprint $table) {
            //
        });
    }
};
