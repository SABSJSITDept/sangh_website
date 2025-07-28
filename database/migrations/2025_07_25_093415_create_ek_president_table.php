<?php

// database/migrations/xxxx_xx_xx_create_ek_president_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ek_president', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('place');
            $table->string('karaykal');
            $table->string('photo'); // path of uploaded photo
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('ek_president');
    }
};
