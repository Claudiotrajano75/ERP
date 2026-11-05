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
        // $schedule->command('alerta:cron')->dailyAt(1,13);
        $schedule->command('alerta:cron')->everyMinute();

        // Envio automático mensal de documentos fiscais e SPED para contadores no dia 1º às 06:00
        $schedule->command('fiscal:enviar-fechamento-contador')->monthlyOn(1, '06:00');

        // Clean up PDV Drafts older than 24 hours
        $schedule->call(function () {
            \App\Models\PdvDraft::where('updated_at', '<', now()->subDays(1))->delete();
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
