<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('news', function (Blueprint $table) {
            $table->string('mode')->default('offline')->after('location'); // online or offline
            $table->string('location_link')->nullable()->after('mode'); // Google link for offline events
        });
    }

    public function down(): void {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['mode', 'location_link']);
        });
    }
};
