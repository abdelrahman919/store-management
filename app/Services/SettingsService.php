<?php

namespace App\Services;

use App\Abstracts\Interfaces\SettingsServiceInterface;
use App\Enums\SettingsKeys;
use App\Models\Settings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingsService implements SettingsServiceInterface
{


    public function getValue(string $key, $default = null)
    {
        $value =  Settings::where('key', $key)->firstOrFail()->value('value');
        return $value;

        /*         return Cache::remember("setting_{$key}", now()->addMinutes(10), function () use ($key, $default) {
            $value =  Settings::where('key', $key)->value('value');
            return $value === null ? $default : $value;
        }); */
    }

    public function getAllValues()
    {
        return Cache::remember('settings', now()->addMinutes(10), function () {
            return Settings::all()->pluck('value', 'key');
        });
    }


    public function setValue(string $key, $value): void
    {
        $setting = Settings::where('key', $key)->first();
        $setting->value = $value;

        $setting->save();


        // Cache::forget("setting_{$key}"); 
    }



}
