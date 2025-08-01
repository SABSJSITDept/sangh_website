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
    Schema::table('it_cell', function (Blueprint $table) {
        $table->unsignedInteger('priority')->default(1)->after('mobile');
    });
}

public function down()
{
    Schema::table('it_cell', function (Blueprint $table) {
        $table->dropColumn('priority');
    });
}

};
