<?php

namespace App\Console\Commands;

use App\Jobs\TelegramMessageJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class CheckTelegramMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fire:check_telegram_message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Telegram Message to Send';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Start send message.');
        Bus::dispatchNow(new TelegramMessageJob());
        $this->info('End send message.');
    }
}
