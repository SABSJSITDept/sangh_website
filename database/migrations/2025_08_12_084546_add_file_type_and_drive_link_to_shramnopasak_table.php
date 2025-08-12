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
    Schema::table('shramnopasak', function (Blueprint $table) {
        $table->enum('file_type', ['pdf', 'drive'])->default('pdf')->after('month');
        $table->string('drive_link')->nullable()->after('pdf');
    });
}

public function down()
{
    Schema::table('shramnopasak', function (Blueprint $table) {
        $table->dropColumn(['file_type', 'drive_link']);
    });
}

};
