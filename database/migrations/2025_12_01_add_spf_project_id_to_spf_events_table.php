<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('spf_events', function (Blueprint $table) {
            $table->foreignId('spf_project_id')
                ->nullable()
                ->constrained('spf_projects')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spf_events', function (Blueprint $table) {
            $table->dropForeign(['spf_project_id']);
            $table->dropColumn('spf_project_id');
        });
    }
};
