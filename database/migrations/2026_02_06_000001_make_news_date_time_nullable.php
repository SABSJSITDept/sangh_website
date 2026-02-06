<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // First clean invalid time data
        DB::table('news')
            ->whereNotNull('time')
            ->where(function($query) {
                $query->where('time', 'like', '%pm%')
                      ->orWhere('time', 'like', '%PM%')
                      ->orWhere('time', 'like', '%am%')
                      ->orWhere('time', 'like', '%AM%');
            })
            ->update(['time' => null]);
            
        // Then modify schema
        Schema::table('news', function (Blueprint $table) {
            $table->date('date')->nullable()->change();
            $table->time('time')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->date('date')->nullable(false)->change();
            $table->time('time')->nullable(false)->change();
        });
    }
};
