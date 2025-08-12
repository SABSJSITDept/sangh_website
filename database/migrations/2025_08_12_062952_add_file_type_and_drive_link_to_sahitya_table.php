<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::table('sahitya', function (Blueprint $table) {
        $table->enum('file_type', ['pdf', 'drive'])->default('pdf');
        $table->string('drive_link')->nullable();
    });
}

public function down()
{
    Schema::table('sahitya', function (Blueprint $table) {
        $table->dropColumn(['file_type', 'drive_link']);
    });
}

};
