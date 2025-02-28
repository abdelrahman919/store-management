<?php

namespace App\Services;

use App\Abstracts\Interfaces\SettingsServiceInterface;
use App\Enums\SettingsKeys;
use App\Models\Settings;
use Illuminate\Support\Facades\Cache;

class SettingsService implements SettingsServiceInterface
{

    public function getValue(SettingsKeys $key, $default = null)
    {
        return Cache::remember("setting_{$key->value}", now()->addMinutes(10), function () use ($key, $default) {
            $value =  Settings::where('key', $key->value)->value('value');
            return $value === null ? $default : $value;
        });
    }


    public function setValue(SettingsKeys $key, $value): void
    {
        Settings::where('key', $key->value)->first()?->update(['value' => $value]);
        Cache::forget("setting_{$key->value}"); 
    }
}
