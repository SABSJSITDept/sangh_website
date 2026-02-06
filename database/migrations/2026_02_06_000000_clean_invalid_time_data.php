<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // First, clean up invalid time data
        DB::table('news')
            ->where('time', 'REGEXP', '[aApP][mM]|[0-9]+:[0-9]+ [aApP][mM]')
            ->update(['time' => null]);
            
        // Also clean up any other invalid time formats
        DB::table('news')
            ->whereNotNull('time')
            ->where('time', 'NOT REGEXP', '^[0-9]{1,2}:[0-9]{2}(:[0-9]{2})?$')
            ->update(['time' => null]);
    }

    public function down(): void
    {
        // Cannot restore invalid data
    }
};