<?php

// database/migrations/xxxx_xx_xx_create_photo_gallery_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('photo_gallery', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['sangh', 'yuva', 'mahila']);
            $table->string('event_name');
            $table->string('photo'); // one row per photo
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('photo_gallery');
    }
};
