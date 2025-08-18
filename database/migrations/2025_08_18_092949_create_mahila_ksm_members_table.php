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
        Schema::create('mahila_ksm_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city')->nullable();
            $table->string('mobile')->nullable(); // ✅ same number allowed
            $table->string('photo')->nullable();

            // ✅ foreign key to aanchals table
            $table->unsignedBigInteger('aanchal_id');
            $table->foreign('aanchal_id')
                  ->references('id')
                  ->on('aanchal')
                  ->onDelete('cascade');

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahila_ksm_members');
    }
};
