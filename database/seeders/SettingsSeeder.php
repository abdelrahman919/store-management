<?php

namespace Database\Seeders;

use App\Models\Settings;
use App\Models\SettingsConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('settings')->truncate();

        foreach (SettingsConfig::getAllConfig() as $key => $data) {
            Settings::firstOrCreate(
                ['key' => $key],
                [
                    'value' => (['value' => $data['default']]),
                    'type' => $data['type']
                ]

            );
        }
    }
}
