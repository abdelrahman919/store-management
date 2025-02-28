<?php
namespace App\Models\Abstracts;

use App\Enums\TransactionDirection;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use App\Services\TransactionService;
use App\Traits\PersistableTransactionHelper;

abstract class PersistableTransaction extends Transaction{
    
    // use PersistableTransactionHelper;

    // protected abstract function persist();

    protected abstract function persist(TransactionService $transactionService, array $data);

    function getSignedValue(): float{
        $factor = 
        $this->getDirection() === TransactionDirection::Incoming ? 
        1 : 
        -1 ;

        return $this->final_price * $factor;
    }

    
    // whether it has items like sale or not like creditpayments
    abstract protected function shouldHaveItems(): bool;






}

