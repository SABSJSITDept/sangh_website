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

        // Har roz raat 12 baje IST (18:30 UTC) panchang fetch karo
        // Agar server Indian timezone pe hai toh dailyAt('00:00') use karo
        // Agar server UTC pe hai toh '18:30' use karo (18:30 UTC = 00:00 IST)
        $schedule->command('panchang:fetch')
                 ->dailyAt('18:30')                    // 18:30 UTC = 00:00 IST (midnight India)
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
