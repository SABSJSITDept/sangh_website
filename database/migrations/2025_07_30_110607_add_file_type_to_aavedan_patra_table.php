<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('aavedan_patra', function (Blueprint $table) {
        $table->string('file_type')->default('pdf'); // 'pdf' or 'google_form'
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aavedan_patra', function (Blueprint $table) {
            //
        });
    }
};
