<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Step 1: Convert invalid time data to valid format or null
        $records = DB::table('news')->whereNotNull('time')->get();
        
        foreach($records as $record) {
            $newTime = $this->convertToValidTime($record->time);
            DB::table('news')
                ->where('id', $record->id)
                ->update(['time' => $newTime]);
        }
        
        // Step 2: Now make columns nullable
        Schema::table('news', function (Blueprint $table) {
            $table->date('date')->nullable()->change();
            $table->time('time')->nullable()->change();
            $table->string('photo')->nullable()->change();
        });
    }
    
    private function convertToValidTime($timeString)
    {
        if (empty($timeString)) {
            return null;
        }
        
        // Convert "08:30 Pm" to "20:30:00" format
        $timeString = trim(strtolower($timeString));
        
        // Check if it's already in valid MySQL TIME format (HH:MM:SS or HH:MM)
        if (preg_match('/^([0-1]?[0-9]|2[0-3]):([0-5][0-9])(:[0-5][0-9])?$/', $timeString)) {
            // Already valid, just ensure HH:MM:SS format
            if (substr_count($timeString, ':') === 1) {
                return $timeString . ':00';
            }
            return $timeString;
        }
        
        // Handle PM times
        if (strpos($timeString, 'pm') !== false) {
            $time = preg_replace('/[^0-9:]/', '', $timeString);
            $parts = explode(':', $time);
            
            if (count($parts) >= 2) {
                $hour = (int)$parts[0];
                $minute = (int)$parts[1];
                
                if ($hour >= 1 && $hour <= 11) {
                    $hour += 12;
                } elseif ($hour == 12) {
                    // 12 PM stays as 12
                }
                
                if ($hour <= 23 && $minute <= 59) {
                    return sprintf('%02d:%02d:00', $hour, $minute);
                }
            }
        }
        
        // Handle AM times
        if (strpos($timeString, 'am') !== false) {
            $time = preg_replace('/[^0-9:]/', '', $timeString);
            $parts = explode(':', $time);
            
            if (count($parts) >= 2) {
                $hour = (int)$parts[0];
                $minute = (int)$parts[1];
                
                if ($hour == 12) {
                    $hour = 0; // 12 AM becomes 00
                }
                
                if ($hour <= 23 && $minute <= 59) {
                    return sprintf('%02d:%02d:00', $hour, $minute);
                }
            }
        }
        
        // If we can't parse it, return null
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