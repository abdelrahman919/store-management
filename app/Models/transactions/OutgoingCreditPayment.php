<?php

namespace App\Models\transactions;

use App\Models\Abstracts\OutgoingTransaction;
use App\Services\TransactionService;

class OutgoingCreditPayment extends OutgoingTransaction
{
    function shouldHaveItems(): bool
    {
        return false;
    }


    function persist(TransactionService $transactionService, array $data)
    {
        $transactionService->persistCreditPyamentTransaction($this, $data);
    }

    // function persist(){
    //     $this->persistCreditPyamentTransaction();
    // }
}
