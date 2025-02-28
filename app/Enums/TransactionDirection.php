<?php

namespace App\Enums;

enum TransactionDirection: string
{
    case Incoming = 'incoming'; 
    case Outgoing = 'outgoing';
}
