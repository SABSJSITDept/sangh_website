<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('photo_gallery', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // sangh, yuva, mahila
            $table->string('event_name');
            $table->json('photos'); // store array of file paths
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('photo_gallery');
    }
};
