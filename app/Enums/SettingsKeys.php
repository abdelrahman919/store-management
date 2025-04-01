<?php

namespace App\Enums;

enum SettingsKeys: string
{
    case APP_NAME = 'appName';
    case IS_DISCOUNT_ALLOWED = 'isDiscountAllowed';
    case MAX_CASHIER_DISCOUNT = 'maxCashierDiscount';
    case MAX_ADMIN_DISCOUNT = 'maxAdminDiscount';


    public static function fromString(string $key): ?self
    {
        if(!$key) {
            throw new \InvalidArgumentException("Key cannot be empty");
        }
        
        foreach (self::cases() as $case) {
            if ($case->value === $key) {
                return $case;
            }
        }
        throw new \InvalidArgumentException("Invalid key: {$key}");
    }

    public function getKeyStringValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

}
