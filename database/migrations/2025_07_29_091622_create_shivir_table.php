<?php

// database/migrations/xxxx_xx_xx_create_shivir_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('shivir', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('date');
            $table->string('location');
            $table->text('description');
            $table->string('photo');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('shivir');
    }
};
