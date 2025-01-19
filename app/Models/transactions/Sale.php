<?php

namespace App\Models\transactions;

use App\Models\abstracts\InconmingTransaction;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends InconmingTransaction
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory;

    
}
