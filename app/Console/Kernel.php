<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\CheckDeadlines::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Verificar vencimientos todos los días a las 9:00 AM
        $schedule->command('check:deadlines')->dailyAt('09:00');
        
        // Opcional: también ejecutar cada hora en horario laboral
        $schedule->command('check:deadlines')
                 ->weekdays()
                 ->between('8:00', '18:00')
                 ->hourly();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}