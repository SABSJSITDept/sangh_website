<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_xx_xx_create_pravarti_sanyojak_table.php
public function up()
{
  Schema::create('pravarti_sanyojak', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('post');
    $table->string('city');

    // 👇 यह लाइन सबसे महत्वपूर्ण है (ID के type से match करना चाहिए)
    $table->unsignedBigInteger('pravarti_id');

    $table->string('mobile');
    $table->string('photo')->nullable();
    $table->timestamps();

    // ✅ Foreign key linking to pravarti.id
    $table->foreign('pravarti_id')
          ->references('id')
          ->on('pravarti')
          ->onDelete('cascade');
});


}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pravarti_sanyojak');
    }
};
