<?php

namespace App\Models\transactions;

use App\Models\abstracts\OutgoingTransaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use function PHPUnit\Framework\isInstanceOf;

class Refund extends OutgoingTransaction
{
    /** @use HasFactory<\Database\Factories\RefundFactory> */
    use HasFactory;
}


