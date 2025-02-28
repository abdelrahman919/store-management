<?php

namespace App\Models\transactions;

use App\Models\Abstracts\IncomingTransaction;
use App\Services\TransactionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends IncomingTransaction
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory;

    function hasItems(): bool
    {
        return true;
    }

    function persist(TransactionService $transactionService, array $data)
    {
        $transactionService->persistItemTransactoin($this, $data);
    }

    // function persist()
    // {
    //     $this->persistItemTransactoin();
    // }

    public function shouldHaveItems(): bool
    {
        return true;
    }


    
}

