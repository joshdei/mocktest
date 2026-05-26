<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Schedule questions weekly (runs every Monday)
        $schedule->command('questions:schedule-weekly')
            ->mondays()
            ->at('00:05');

        // Notify question of the day daily at 8:00 AM
        $schedule->command('questions:notify-today')
            ->daily()
            ->at('08:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}

