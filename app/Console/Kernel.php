<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected $commands = [
        Commands\CheckTelegramMessage::class,
        Commands\MigrateTelegramUserBot::class,
        Commands\ReconnectDaily::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('daily:cron')->daily();
        // $schedule->command('inspire')->hourly();
        $schedule->command('fire:check_telegram_message')->everyMinute();
        // $schedule->command('fire:reconnect_daily')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        //$this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
