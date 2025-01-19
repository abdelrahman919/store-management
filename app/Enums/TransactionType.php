<?php



namespace App\Enums;

enum TransactionType: string
{
    case IncomingTransaction = 'IncomingTransaction';
    case OutgoingTransaction = 'OutgoingTransaction';
}