<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_add_category_and_preference_to_aavedan_patra_table.php

public function up()
{
    Schema::table('aavedan_patra', function (Blueprint $table) {
        $table->string('category')->after('file_type');
        $table->integer('preference')->default(0)->after('category');
    });
}

public function down()
{
    Schema::table('aavedan_patra', function (Blueprint $table) {
        $table->dropColumn(['category', 'preference']);
    });
}

};
