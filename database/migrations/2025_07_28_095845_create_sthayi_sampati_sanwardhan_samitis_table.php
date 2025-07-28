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
    Schema::create('sthayi_sampati_sanwardhan_samitis', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('city');
        $table->string('mobile');
        $table->string('photo')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sthayi_sampati_sanwardhan_samitis');
    }
};
