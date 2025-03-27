<?php

namespace Database\Seeders;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::truncate();

        $data = [
            [
                'title'         => 'Telegram Id',
                'key'           => 'telegram_id',
                'value'         => null,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title'         => 'Remove Link',
                'key'           => 'remove_link',
                'value'         => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title'         => 'Filter Word',
                'key'           => 'filter_word',
                'value'         => null,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
        ];

        Setting::insert($data);
    }
}
