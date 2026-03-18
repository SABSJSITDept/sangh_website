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
        Schema::create('mrm_app', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedBigInteger('relation_id')->nullable();
            $table->string('guardian_name')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->date('birth_day')->nullable();
            $table->string('education')->nullable();
            $table->string('occupation')->nullable();
            $table->string('address_type')->nullable();
            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->string('post')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('pincode')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('mobile')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('alternate_number')->nullable();
            $table->string('email_address')->nullable();
            $table->string('adhar_name')->nullable();
            $table->string('adharfatherName')->nullable();
            $table->string('adhar1')->nullable();
            $table->string('adhar2')->nullable();
            $table->string('adhar3')->nullable();
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed'])->nullable();
            $table->string('rel_faith')->nullable();
            $table->unsignedBigInteger('member_id')->unique()->nullable();
            $table->unsignedBigInteger('family_id')->nullable();
            $table->tinyInteger('app_status')->default(0)->comment('0: Invalid, 1: Valid');
            $table->tinyInteger('registration')->default(1)->comment('0: Not Registered, 1: Registered');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mrm_app');
    }
};
