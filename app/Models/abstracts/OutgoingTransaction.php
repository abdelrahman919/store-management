<?php
namespace App\Models\abstracts;

use App\Enums\TransactionDirection;
use App\Models\Abstracts\PersistableTransaction;

abstract class OutgoingTransaction extends PersistableTransaction{

    function getDirection(): TransactionDirection
    {
        return TransactionDirection::Outgoing;
    }

}


