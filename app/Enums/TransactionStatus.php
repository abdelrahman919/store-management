<?php

// DEPRECATED

namespace App\Enums;

enum TransactionStatus: string
{
    case Credit = 'credit';
    case Paid = 'paid';
}