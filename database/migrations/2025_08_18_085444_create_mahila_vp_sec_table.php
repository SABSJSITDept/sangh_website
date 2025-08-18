<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('mahila_vp_sec', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('post', ['उपाध्यक्ष', 'मंत्री']);
            $table->string('city');
            $table->string('mobile', 15);
            $table->unsignedBigInteger('aanchal_id');
            $table->string('photo');
            $table->timestamps();

            // ⚡ Check karo tumhari DB me table ka naam kya hai:
            // Agar "aanchals" hai to niche line sahi hai
            // Agar "aanchal" hai to "aanchal" likhna hoga
            $table->foreign('aanchal_id')
                  ->references('id')
                  ->on('aanchal')   // 👈 yahan ensure karo table name same ho
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('mahila_vp_sec');
    }
};
