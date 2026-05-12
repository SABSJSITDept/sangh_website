<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        // Har roz DOPAHAR 12:00 baje IST cron chalti hai
        // Aur KAL (agle din) ki date ka panchang data fetch hota hai
        // 12:00 IST = 06:30 UTC
        $schedule->command('panchang:fetch')
                 ->dailyAt('06:30')                    // 06:30 UTC = 12:00 IST (dopahar)
                 ->timezone('UTC')
                 ->withoutOverlapping()
                 ->runInBackground()
                 ->appendOutputTo(storage_path('logs/panchang-cron.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
