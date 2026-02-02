<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('vihar', function (Blueprint $table) {
            $table->dropColumn('aadi_thana');
        });
    }

    public function down(): void {
        Schema::table('vihar', function (Blueprint $table) {
            $table->string('aadi_thana');
        });
    }
};
