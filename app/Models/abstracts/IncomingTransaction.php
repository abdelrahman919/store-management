<?php
namespace App\Models\Abstracts;

use App\Enums\TransactionDirection;
use App\Models\Transaction;
use OpenApi\Examples\Polymorphism\Fl;

abstract class IncomingTransaction extends PersistableTransaction{
    
    function getDirection(): TransactionDirection{
        return TransactionDirection::Incoming;
    }




}


