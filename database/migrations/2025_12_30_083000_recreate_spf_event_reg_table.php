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
        // Drop the old table first
        Schema::dropIfExists('spf_event_reg');

        // Create new simplified table
        Schema::create('spf_event_reg', function (Blueprint $table) {
            $table->id();
            $table->string('member_id')->nullable();
            $table->unsignedBigInteger('event_id');
            $table->string('response')->default('yes'); // yes, no, maybe
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('spf_events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spf_event_reg');
    }
};
