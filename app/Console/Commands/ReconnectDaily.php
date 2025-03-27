<?php

namespace App\Console\Commands;

use App\Models\TelegramBot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use GuzzleHttp\Client;

class ReconnectDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fire:reconnect_daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reconnect daily';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $telegram_bots = TelegramBot::all();

        foreach ($telegram_bots as $telegram_bot) {
            $baseUrl = url('/');
            // dd($baseUrl);
            $url = $baseUrl."/api/webhook/".$telegram_bot->id;

            $client = new Client();

            $response = $client->post('https://api.telegram.org/bot'.$telegram_bot->bot_token.'/setWebhook', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'url' => $url,
                ],
            ]);

            $telegram_bot->update(['connected'=>0]);
            if ($response->getStatusCode() === 200) {
                $telegram_bot->update(['connected'=>1]);
                $envPath = base_path('.env');
                $newTokenLine = 'TELEGRAM_' . $telegram_bot->id . '="' . $telegram_bot->bot_token . '"' . PHP_EOL;

                if (File::exists($envPath)) {
                    $envContents = File::get($envPath);

                    $pattern = '/^TELEGRAM_' . preg_quote($telegram_bot->id, '/') . '=.*$/m';
                    if (preg_match($pattern, $envContents)) {
                        $envContents = preg_replace($pattern, trim($newTokenLine), $envContents);
                    } else {
                        $envContents .= $newTokenLine;
                    }

                    File::put($envPath, $envContents);
                }
            } else {
                return "Failed";
            }
        }
    }
}
