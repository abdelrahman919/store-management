<?php

namespace App\Models\transactions;

use App\Models\Abstracts\OutgoingTransaction;
use App\Services\TransactionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Refund extends OutgoingTransaction
{
    /** @use HasFactory<\Database\Factories\RefundFactory> */
    use HasFactory;

    function shouldHaveItems(): bool
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
}


