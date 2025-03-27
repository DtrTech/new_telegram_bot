<?php

namespace App\Console\Commands;

use App\Models\TelegramUser;
use App\Models\TelegramUserBot;
use Illuminate\Console\Command;
use DB;

class MigrateTelegramUserBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migration:telegram-user-bot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $telegram_users = TelegramUser::whereNull('migrated')->where('pm_bot', 1)->limit(500)->get();

        foreach ($telegram_users as $telegram_user) {
            TelegramUserBot::firstOrCreate([
                'telegram_user_id' => $telegram_user->id,
                'telegram_bot_id' => $telegram_user->telegram_bot_id,
            ]);
            DB::table('telegram_users')->where('id', $telegram_user->id)->update([
                'migrated' => 1
            ]);
        }
    }
}
