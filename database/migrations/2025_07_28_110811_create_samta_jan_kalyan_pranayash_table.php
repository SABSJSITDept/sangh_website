<?php

// database/migrations/xxxx_xx_xx_create_samta_jan_kalyan_pranayash_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('samta_jan_kalyan_pranayash', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city');
            $table->string('mobile');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('samta_jan_kalyan_pranayash');
    }
};

