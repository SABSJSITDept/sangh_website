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
    Schema::create('sthayi_sampati_sanwardhan_samiti', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('post');
        $table->string('city');
        $table->string('mobile_number', 15);
        $table->string('photo');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('sthayi_sampati_sanwardhan_samiti');
}

};
