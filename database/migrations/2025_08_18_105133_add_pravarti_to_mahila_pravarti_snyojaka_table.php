<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('mahila_pravarti_snyojaka', function (Blueprint $table) {
        $table->string('pravarti')->nullable()->after('post');
    });
}

public function down(): void
{
    Schema::table('mahila_pravarti_snyojaka', function (Blueprint $table) {
        $table->dropColumn('pravarti');
    });
}

};
