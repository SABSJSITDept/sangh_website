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
        Schema::table('spf_committee', function (Blueprint $table) {
            $table->unsignedBigInteger('anchal_id')->nullable()->after('post');
            $table->foreign('anchal_id')->references('id')->on('aanchal')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spf_committee', function (Blueprint $table) {
            $table->dropForeign(['anchal_id']);
            $table->dropColumn('anchal_id');
        });
    }
};