<?php

namespace App\Models;

use App\Enums\SettingsKeys;

class SettingsConfig
{
    // Add new settings ONLY here and add the key to SettingsKeys enum
    // no need to modify the Settings class
    private static array $config = [
        SettingsKeys::APP_NAME->value => [
            'type' => 'string',
            'default' => 'My App',
            'validation' => ['string', 'max:255'],
        ],
        SettingsKeys::IS_DISCOUNT_ALLOWED->value => [
            'type' => 'boolean',
            'default' => false,
            'validation' => ['boolean'],
        ],
        SettingsKeys::MAX_CASHIER_DISCOUNT->value => [
            'type' => 'float',
            'default' => 0.0,
            'validation' => ['numeric', 'min:0', 'max:100'],
        ],
        SettingsKeys::MAX_ADMIN_DISCOUNT->value => [
            'type' => 'float',
            'default' => 0.0,
            'validation' => ['numeric', 'min:0', 'max:100'],
        ],
    ];

    public static function getType(string $key): string
    {
        return self::$config[$key]['type'];
    }

    public static function getDefault(string $key)
    {
        return self::$config[$key]['default'];
    }

    public static function getValidation(string $key)
    {
        return self::$config[$key]['validation'];
    }


/*     public static function getType(SettingsKeys $key): string
    {
        return self::$config[$key->vlaue]['type'];
    }

    public static function getDefault(SettingsKeys $key)
    {
        return self::$config[$key->vlaue]['default'];
    } 

    public static function getValidation(SettingsKeys $key)
    {
        return self::$config[$key->vlaue]['validation'];
    }
*/
    public static function getAllConfig(): array
    {
        return self::$config;
    }



}
