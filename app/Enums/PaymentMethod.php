<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case Visa = 'visa';
    case Credit = 'credit';
    case InstaPay = 'instapay';


    static function getValues(): array{
        return array_map(fn($case)=>$case->value, self::cases());
    }

}


