<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Step 1: First make columns nullable to avoid constraint violations
        Schema::table('news', function (Blueprint $table) {
            $table->date('date')->nullable()->change();
            $table->time('time')->nullable()->change();
            $table->string('photo')->nullable()->change();
        });
        
        // Step 2: Now convert invalid time formats to valid MySQL TIME format
        $invalidTimes = DB::table('news')
            ->whereNotNull('time')
            ->where(function($query) {
                $query->where('time', 'like', '%pm%')
                      ->orWhere('time', 'like', '%PM%')
                      ->orWhere('time', 'like', '%am%')
                      ->orWhere('time', 'like', '%AM%');
            })
            ->get();
            
        foreach($invalidTimes as $record) {
            $cleanTime = $this->convertToValidTime($record->time);
            DB::table('news')
                ->where('id', $record->id)
                ->update(['time' => $cleanTime]);
        }
    }
    
    private function convertToValidTime($timeString)
    {
        // Convert "08:30 Pm" to "20:30:00" format
        $timeString = trim(strtolower($timeString));
        
        if (strpos($timeString, 'pm') !== false) {
            $time = str_replace(['pm', ' '], '', $timeString);
            $parts = explode(':', $time);
            $hour = (int)$parts[0];
            $minute = isset($parts[1]) ? $parts[1] : '00';
            
            if ($hour != 12) {
                $hour += 12;
            }
            return sprintf('%02d:%02d:00', $hour, $minute);
            
        } elseif (strpos($timeString, 'am') !== false) {
            $time = str_replace(['am', ' '], '', $timeString);
            $parts = explode(':', $time);
            $hour = (int)$parts[0];
            $minute = isset($parts[1]) ? $parts[1] : '00';
            
            if ($hour == 12) {
                $hour = 0;
            }
            return sprintf('%02d:%02d:00', $hour, $minute);
        }
        
        // If already valid or unknown format, return null
        return null;
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->date('date')->nullable(false)->change();
            $table->time('time')->nullable(false)->change();
        });
    }
};