<?php

namespace App\Models\transactions;

use App\Models\Abstracts\IncomingTransaction;
use App\Services\TransactionService;

class IncomingCreditPayment extends IncomingTransaction
{

    // function hasItems(): bool
    // {
    //     return false;
    // }

    function shouldHaveItems(): bool
    {
        return false;
    }


    function persist(TransactionService $transactionService, array $data)  
    {
        $transactionService->persistCreditPyamentTransaction($this, $data);
    }

    // function persist()
    // {
    //     $this->persistCreditPyamentTransaction();
    // }


}
